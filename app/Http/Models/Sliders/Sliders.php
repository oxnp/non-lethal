<?php

namespace App\Http\Models\Sliders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use DB;
class Sliders extends Model
{
    protected $table = 'sliders';
    public $timestamps = false;
    protected $fillable = ['title','sub_title','link','lang_id','image'];

    public static function getLastid(){
        $last_id = Sliders::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }

    public static function getAllSlides(){
        $slides = Sliders::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();;

        return $slides;
    }

    public static function getSlide($id){

        $static_page = Sliders::find($id);
        $page = Sliders::whereRelation($static_page->relation)->get();
        return $page;


        return $slide;
    }

    public static function updateSlide($request, $storage_image,$link){
        $data = array();
        $page_ids=  array();
        foreach($request->all() as $key=>$value){
            if (is_array($value)){
                foreach($value as $lang_id=>$val){
                    $data[array_key_first($val)][$key] = $request->$key[$lang_id][array_key_first($val)];
                    $data[array_key_first($val)]['link'] = $link;
                    $page_ids[array_key_first($val)] = array_key_first($val);
                }
            }

        }

        foreach($page_ids as $id){
            Sliders::find($id)->update($data[$id]);
            if($storage_image != ''){
                Sliders::find($id)->update(['image'=>$storage_image]);
            }

        }
        return true;
    }

    public static function storeSlide($request,$storage_image,$link){
$str = str_random(5);
        $data = array();
        foreach($request->all() as $key=>$value){
            if (is_array($value)){
                foreach($value as $lang_id=>$val){
                    $data[$lang_id][$key] = $val;
                    $data[$lang_id]['lang_id'] = $lang_id;
                    $data[$lang_id]['link'] = $link;
                    $data[$lang_id]['relation'] = $str;
                    if($storage_image != ''){
                        $data[$lang_id]['image'] = $storage_image;
                    }
                }
            }
        }


        Sliders::insert($data);
    }
}
