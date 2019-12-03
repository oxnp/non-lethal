<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductsPage extends Model
{
    protected $table = 'products_page';

    public static function getPageByCategoryId($category_id){
        $data = ProductsPage::whereCategoryId($category_id)->whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
      //  dd($data);
        return $data;
    }

    public static function getPage($slug){
        $data = ProductsPage::whereSlug($slug)->whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $data;
    }
}
