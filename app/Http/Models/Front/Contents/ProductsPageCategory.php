<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductsPageCategory extends Model
{
    protected $table = "products_page_categories";

    public static function getCategory($category){
        $category_data = ProductsPageCategory::whereSlug($category)->whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        $result = array();
        $pages = collect();
        if ($category_data->toArray()[0]['content_flag'] == 0){
            $pages = ProductsPage::getPageByCategoryId($category_data[0]['id']);
        }
        $result['pages'] = $pages;
        $result['category'] = $category_data;

        return $result;
    }
}
