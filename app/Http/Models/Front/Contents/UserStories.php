<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UserStories extends Model
{
    public static function getStories(){
        $stories = UserStories::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $stories;
    }

    public static function getStory($slug){
        $story = UserStories::whereSlug($slug)->whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $story;
    }
}
