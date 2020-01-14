<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    public static function getNews(){
        $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->paginate(9);

        return $news;
    }

    public static function getNewsBySlug($slug){
        $news = News::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->whereSlug($slug)->get();
        return $news;
    }
}
