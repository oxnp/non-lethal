<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use DB;

class StaticPages extends Model
{
    protected $fillable = ['title','content'];
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

    public static function updatePage($request){

        foreach($request->title as $lang_id=>$title){
            foreach($title as $page_id=>$value){
                StaticPages::find($page_id)->update([
                    'title'=>$value,
                ]);
            }
        }

        foreach($request->content as $lang_id=>$content){
            foreach($content as $page_id=>$value){
                StaticPages::find($page_id)->update([
                    'content'=>$value,
                ]);
            }
        }

        return true;
    }
}
