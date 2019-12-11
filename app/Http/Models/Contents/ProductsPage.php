<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductsPage extends Model
{
    protected $fillable = ['title','slug','sub_title','short_text','category_id','image','content'];
    public $timestamps = false;
    protected $table = 'products_page';
    public static function getPages(){
        $products_pages = ProductsPage::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $products_pages;
    }

    public static function getPage($id){
        $product_page = ProductsPage::find($id);
        $page = ProductsPage::whereSlug($product_page->slug)->get();
        return $page;
    }

    public static function updatePage($request,$storage_image){

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
            ProductsPage::find($id)->update($data[$id]);
            if($storage_image != ''){
                ProductsPage::find($id)->update(['image'=>$storage_image]);
            }
        }

        return true;
    }
}
