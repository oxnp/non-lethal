<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductsPage extends Model
{
    protected $table = 'products_page';
    public static function getPages(){
        $products_pages = ProductsPage::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $products_pages;
    }

    public static function getPage($id){
        $product_page = StaticPages::find($id);
        $page = StaticPages::whereSlug($product_page->slug)->get();
        return $page;
    }
}
