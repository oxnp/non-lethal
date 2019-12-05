<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductsPageCategories extends Model
{
    protected $fillable = ['name','content','sub_name','relation','slug'];
    protected $table = 'products_page_categories';
    public $timestamps = false;

    public static function getCategories(){
        $products_pages_categories = ProductsPageCategories::all();
        return $products_pages_categories;
    }

    public static function getCategoriesTolist(){
        $products_pages_categories = ProductsPageCategories::where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))->get();
        return $products_pages_categories;
    }

    public static function getCategory($id){
        $product_page_category = ProductsPageCategories::find($id);
        $category_page = ProductsPageCategories::whereSlug($product_page_category->slug)->get();
        return $category_page;
    }

    public static function updateCategory($request){

        $data = array();
        $page_ids=  array();
        foreach($request->all() as $key=>$value){
            if (is_array($value)){
                foreach($value as $lang_id=>$val){
                    $data[array_key_first($val)][$key] = $request->$key[$lang_id][array_key_first($val)];
                    $page_ids[array_key_first($val)] = array_key_first($val);
                }
            }
        }

        foreach($page_ids as $id){
            ProductsPageCategories::find($id)->update($data[$id]);
        }
    }




}
