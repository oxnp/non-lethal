<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductsPageCategories extends Model
{
    protected $table = 'products_page_categories';

    public static function getCategories(){
        $products_pages = ProductsPageCategories::all();
        return $products_pages;
    }


}
