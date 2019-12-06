<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use DB;

class StaticPages extends Model
{
    protected $fillable = ['title','content','slug','image'];
    public $timestamps = false;
    protected $table = 'static_page';

    public static function getStaticPages(){
        $static_pages = StaticPages::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $static_pages;
    }

    public static function getPage($id){
        $static_page = StaticPages::find($id);
        $page = StaticPages::whereSlug($static_page->slug)->get();
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
            StaticPages::find($id)->update($data[$id]);
            if($storage_image != ''){
                StaticPages::find($id)->update(['image'=>$storage_image]);
            }
        }
        return true;
    }


    public static function addPage($request,$storage_image){

        $data = array();
        foreach($request->all() as $key=>$value){
            if (is_array($value)){
                foreach($value as $lang_id=>$val){
                    $data[$lang_id][$key] = $val;
                    $data[$lang_id]['lang_id'] = $lang_id;
                    if($storage_image != ''){
                        $data[$lang_id]['image'] = $storage_image;
                    }
                }
            }
        }

        StaticPages::insert($data);
        return true;
    }

    public static function getLastid(){
        $last_id = StaticPages::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }


}
