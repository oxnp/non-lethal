<?php

namespace App\Http\Models\Front\MyLicenses;

use App\Http\Models\Front\Products\Products;
use Illuminate\Database\Eloquent\Model;
use Auth;

class MyLicenses extends Model
{
    protected $table = "licenses";
    public static function getLicensesByUser(){
        $product_ids = MyLicenses::whereBuyerId(93)->select('product_id')->get();
        dd($product_ids->toArray());

        $products = Products::whereIdIn();

        return $product_ids;
    }
}
