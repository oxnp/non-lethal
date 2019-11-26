<?php

namespace App\Http\Models\Paddle;

use App\Http\Models\Products\Products;
use Illuminate\Database\Eloquent\Model;
use Cache;
class Paddle extends Model
{

    private $vendorID = NULL;
    private $authCode = NULL;
    private static $postParams = NULL;

    public function __construct()
    {
        // Get params and assign Paddle API credentials
        $this->vendorID = env('PADDLE_VENDOR_ID');
        $this->authCode =  env('PADDLE_VENDOR_AUTH_CODE');

        // Generate base post request params string
        if (!empty($this->vendorID) && !empty($this->authCode)) {
            self::$postParams = 'vendor_id=' . $this->vendorID;
            self::$postParams .= '&vendor_auth_code=' . $this->authCode;
        }
    }

    public static function getOptions($id = 0){

        $paddleProductList = parent::getPaddleProductsList();

        $assigned_paddle_pids = Products::getAssignedPaddlePIDs($id);



        $pid_result = array();
        foreach($assigned_paddle_pids as $paddle_pids){
            $pid_result[] = $paddle_pids['pid'];
        }
        try {
            $type_product = Products::getProductTypeById($id);
        }catch (\Exception $e){
            $type_product = env('LICENSE_TYPE_BASE');
        }

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

    public static function getPaddleProductsList() {
        // Get component params
        if (empty(self::$postParams)) {
            return false;
        }

        $cachedData = json_decode(Cache::get('CACHE_PADDLE_PRODUCTS_LIST'));

         if(($cachedData !== false) && !empty($cachedData)) {
           return $cachedData;
          }

        // Send product list request to Paddle
        $completeList = (object)array();
        $productsList = self::askPaddle(env('JAA_PADDLE_API_GET_PRODUCTS_LIST'), array(), false);

        if(boolval($productsList->success) == true) {
            $completeList->products = $productsList->response->products;
        }

        // Send subscription plan list request to Paddle
        $subscriptionsList = self::askPaddle(env('JAA_PADDLE_API_GET_SUBSCRIPTION_PLANS'));
        if(boolval($subscriptionsList->success) == true) {
            $completeList->subscriptions = $subscriptionsList->response;
        }

        if(!empty($completeList)) {

            Cache::put('CACHE_PADDLE_PRODUCTS_LIST',json_encode($completeList),120);

            return $completeList;
        }
        return false;
    }

    private static function askPaddle($apiPath, $params = array(), $autoCloseConnection = true) {

        // Append params to main post params
        $allParams = self::$postParams;
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

}
