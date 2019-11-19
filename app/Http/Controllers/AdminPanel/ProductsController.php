<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Products\MainProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Products\Products;
use App\Http\Models\Users\UserRole;
use App\Http\Models\Paddle\Paddle;

class ProductsController extends Controller
{

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

        $options_paddle_list = Paddle::getOptions($id);

        return view('AdminPanel.products.product_show')->with([
            'product' => $product,
            'accesses' => $accesses,
            'upgradeable_products' => $upgradeable_products,
            'options_paddle_list'=>$options_paddle_list
        ]);

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
        try  {
            Products::updateProductById($request,$id);
            return redirect()->back();
        }catch(RuntimeException $e){

        }

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
