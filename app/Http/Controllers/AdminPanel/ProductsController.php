<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Products\MainProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Products\Products;
use App\Http\Models\Users\UserRole;




class ProductsController extends Controller
{

    private $vendorID = NULL;
    private $authCode = NULL;
    private $postParams = NULL;

    public function __construct()
    {
        // Get params and assign Paddle API credentials
        $this->vendorID = env('PADDLE_VENDOR_ID');
        $this->authCode =  env('PADDLE_VENDOR_AUTH_CODE');

        // Generate base post request params string
        if (!empty($this->vendorID) && !empty($this->authCode)) {
            $this->postParams = 'vendor_id=' . $this->vendorID;
            $this->postParams .= '&vendor_auth_code=' . $this->authCode;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = 20;
        if ($request->per_page != null){
            $per_page = $request->per_page;
        }
        $main_products = MainProducts::getMainProducts();
        $all_products = Products::all()->toArray();
        $products_links = Products::getProducts($per_page)->appends(['per_page' => $per_page]);
        $products = Products::getProducts($per_page)->appends(['per_page' => $per_page])->toArray();


        $data = array();

        foreach($products['data'] as $key=>$product) {
            $legacy_names = array();
            $upd_prod_list = array();
            if ($product['upgradeable_products'] != '') {
                $legacy_ids = $this->getLegacyProductsId(json_decode($product['upgradeable_products']));
                $legacy_names = $main_products->map(function ($prod) use ($legacy_ids, $legacy_names) {
                    foreach ($legacy_ids as $value) {
                        if ($prod->id == $value) {
                            $legacy_names[] = $prod->prod_desc;
                        }
                    }
                    return $legacy_names;
                });

                foreach ($all_products as $prod) {
                    if ($product['upgradeable_products'] != '') {
                        foreach (json_decode($product['upgradeable_products']) as $key => $upd_product_id) {

                            if ($prod['id'] == (Int)$upd_product_id) {
                                $upd_prod_list[] = $prod['name'];
                            }
                        }
                    }
                }
            }

            if (!empty($legacy_names)) {
                $legacy_names = $legacy_names->toArray();
                $result = [];
                array_walk_recursive($legacy_names, function ($item, $key) use (&$result) {
                    $result[] = $item;
                });
                $merge = array_merge($result, $upd_prod_list);
            }else{
                $merge = array_merge($legacy_names, $upd_prod_list);
            }


            $data['products'][] = array(
                'id'=>$product['id'],
                'published'=>$product['published'],
                'name'=>$product['name'],
                'access'=>$product['access_level'],
                'upgradeable_products'=>$merge,
                'code'=>$product['code'],
                'paddle_pid'=>$product['paddle_pid'] == '' ? '-' : $product['paddle_pid'],
                'paddle_upgrade_pid'=>$product['paddle_upgrade_pid'] == '' ? '-' : $product['paddle_upgrade_pid'],
                'default_majver'=>$product['default_majver'],
            );
        }

        return view('AdminPanel.products.products_list')->with([
            'products' => $data['products'],
            'products_links' =>$products_links
        ]);
    }

    public function getLegacyProductsId($legacyIDs){

        $filteredIDs = array();
        foreach($legacyIDs as $key => $legacyID) {
            if(preg_match("/([0-9]+)L$/i", $legacyID, $matches)) {
                $filteredIDs[] = $matches[1];
            }
        }
        return $filteredIDs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::getProductById($id)->toArray();
        $accesses = UserRole::all()->toArray();
        $upgradeable_products = Products::getUpgradeableProduct();

        $options_paddle_list = $this->getOptions($id);

        return view('AdminPanel.products.product_show')->with([
            'product' => $product,
            'accesses' => $accesses,
            'upgradeable_products' => $upgradeable_products,
            'options_paddle_list'=>$options_paddle_list
        ]);

    }

    public function getOptions($id){

        $paddleProductList = $this->getPaddleProductsList();

        $assigned_paddle_pids = Products::getAssignedPaddlePIDs($id);
        $pid_result = array();
        foreach($assigned_paddle_pids as $paddle_pids){
            $pid_result[] = $paddle_pids['pid'];
        }
        $type_product = Products::getProductTypeById($id);

        $productTypeGroup = ($type_product == env('LICENSE_TYPE_BASE')) ? 'products' : 'subscriptions';
        $products = array();

        if(!empty($paddleProductList)) {
            foreach($paddleProductList->$productTypeGroup as $product) {

                $inUse = in_array($product->id, $pid_result);
                $products[] = array(
                    'text' => $product->name . ($inUse ? ' (in use)' : ''),
                    'value' => $product->id,
                    'disable' => $inUse
                );
            }
        }
        return $products;
    }

    public function getPaddleProductsList() {

        // Get component params
        if (empty($this->postParams)) {
            return false;
        }

        // Try to get products list from cache
       // $jCache = JFactory::getCache(JAA_CACHE_GROUP, '');
      //  $cachedData = $jCache->get(JAA_CACHE_PADDLE_PRODUCTS_LIST);

        //var_dump($cachedData);

       // if(($cachedData !== false) && !empty($cachedData)) {
         //   return $cachedData;
      //  }

        // Send product list request to Paddle
        $completeList = (object)array();
        $productsList = $this->askPaddle(env('JAA_PADDLE_API_GET_PRODUCTS_LIST'), array(), false);


        if(boolval($productsList->success) == true) {
            $completeList->products = $productsList->response->products;
        }

        // Send subscription plan list request to Paddle
        $subscriptionsList = $this->askPaddle(env('JAA_PADDLE_API_GET_SUBSCRIPTION_PLANS'));
        if(boolval($subscriptionsList->success) == true) {
            $completeList->subscriptions = $subscriptionsList->response;
        }

        if(!empty($completeList)) {

            // Store products list to Joomla cache
            //$jCache->store($completeList, env('JAA_CACHE_PADDLE_PRODUCTS_LIST'));

            return $completeList;
        }
        return false;
    }


    private function askPaddle($apiPath, $params = array(), $autoCloseConnection = true) {

        // Append params to main post params
        $allParams = $this->postParams;
        foreach($params as $key => $value) {
            $allParams .= '&' . $key . '=' . $value;
        }
        $cRequest = curl_init();
        curl_setopt($cRequest, CURLOPT_URL, env('JAA_PADDLE_API_BASE_URL') . $apiPath);
        curl_setopt($cRequest, CURLOPT_POSTFIELDS, $allParams);
        curl_setopt($cRequest, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($cRequest, CURLOPT_CONNECTTIMEOUT, 20);          // 20 secs connection time limit
        $cResponse = curl_exec($cRequest);

        if($autoCloseConnection) {
            curl_close($cRequest);
        }
        if(false === $cResponse) {
            return (object)array(
                'success' => false
            );
        }
        return json_decode($cResponse);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
