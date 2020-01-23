<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    public static function getNews(){

        if(Auth::guest()){
            $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->where('published',1)->orderBy('created_at','DESC')->paginate(9);
            return $news;
        }

        if(Auth::user()->role_id == 1) {
            $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->orderBy('created_at','DESC')->paginate(9);
            return $news;
        }else{
            $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->where('published',1)->orderBy('created_at','DESC')->paginate(9);
            return $news;
        }

    }

    public static function getNewsBySlug($slug){
        $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->whereSlug($slug)->get();
        return $news;
    }
}
