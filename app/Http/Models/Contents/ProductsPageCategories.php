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
                    $data[$lang_id]['slug'] = $request->slug;
                    $data[$lang_id]['auth_visible'] = $request->auth_visible;
                }
            }
        }

        foreach($page_ids as $id){
            ProductsPageCategories::find($id)->update($data[$id]);
        }
    }

    public static function store($request){
       // dd($request->all());
        $relation = str_random(5);
        $data = array();
        $page_ids=  array();
        foreach($request->all() as $key=>$value){
            if (is_array($value)){
                foreach($value as $lang_id=>$val){
                    $data[$lang_id][$key] = $val;
                    $data[$lang_id]['lang_id'] = $lang_id;
                    $data[$lang_id]['relation'] = $relation;
                    $data[$lang_id]['slug'] = $request->slug;
                    $data[$lang_id]['auth_visible'] = $request->auth_visible;
                }
            }
        }
        $result = ProductsPageCategories::insert($data);
        return ($result);
    }


    public static function getLastid(){
        $last_id = ProductsPageCategories::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0];
    }

}
