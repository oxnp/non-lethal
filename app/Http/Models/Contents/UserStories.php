<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class UserStories extends Model
{
    protected $fillable = ['title','slug','sub_title','content','image'];
    protected $table = 'user_stories';

    public static function getUserStories(){
        $news = UserStories::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $news;
    }
    public static function getUserStoriesById($id){
        $news = UserStories::find($id);
        $news_data = UserStories::whereSlug($news->slug)->get();
        return $news_data;
    }
    public static function updateUserStories($request,$storage_image){
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
            UserStories::find($id)->update($data[$id]);
            if($storage_image != ''){
                UserStories::find($id)->update(['image'=>$storage_image]);
            }
        }
    }
    public static function addUserStory($request){

    }
    public static function getLastid(){
        $last_id = UserStories::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }
}
