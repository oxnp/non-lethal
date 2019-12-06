<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    protected $fillable = ['title','image','short_text','lang_id','content','slug'];
    public $timestamps = false;

    public static function getNews(){
        $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $news;
    }
    public static function getNewsById($id){
        $news = News::find($id);
        $news_data = News::whereSlug($news->slug)->get();
        return $news_data;
    }
    public static function updateNews($request,$storage_image){
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
            News::find($id)->update($data[$id]);
            if($storage_image != ''){
                News::find($id)->update(['image'=>$storage_image]);
            }
        }
    }

    public static function addNews($request,$storage_image){

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
        News::insert($data);
        return true;
    }

    public static function getLastid(){
        $last_id = News::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }

}
