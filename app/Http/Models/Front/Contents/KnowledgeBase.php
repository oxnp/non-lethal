<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Models\Front\Contents\Categories;

class KnowledgeBase extends Model
{
    public static function getPage($slug){

        $content = Pages::whereSlug("$slug")->where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))->select('content')->get()->toArray();

         return $content[0]['content'];
    }

    public static function getCategoryPage($headcategory,$category,$subcategory = ''){

        $data = [];

        $cats_head = Categories::whereId(DB::raw('(select main_category from categories where slug = "' . $category . '" and lang_id = (select id from languages where locale = "' . App::getLocale() . '"))'))
            ->orWhere('slug', $category)
            ->where('lang_id', DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))
            ->get();

        $filtered = $cats_head->filter(function ($item) {
            if($item['main_category'] != 0) {
                return $item;
            }
        });

        $data['title_page'] = $filtered[1]->title;

        $cats = Categories::whereMainCategory($filtered[1]->id)->get();
        $merge = $cats_head->merge($cats)->toArray();
        $pages = Pages::whereIn('category_id',$cats->pluck('id')->toArray())->get()->toArray();

        $array_cats_head = $cats_head->toArray();

        if ($subcategory == '') {
            foreach ($pages as $slug) {
                foreach ($merge as $cat_slug) {
                    if ($slug['category_id'] == $cat_slug['id']) {
                        $data['list'][$slug['category_id']]['url'] = '/' . $array_cats_head[0]['slug'] . '/' . $array_cats_head[1]['slug'] . '/' . $cat_slug['slug'] . '/' . $slug['slug'];
                        $data['list'][$slug['category_id']]['title'] = $slug['title'];
                        $data['list'][$slug['category_id']]['content'] = self::cut_by_words(400,strip_tags($slug['content']));
                        //$data['list'][$slug['category_id']]['cat_name'] = $cat_slug['title'];
                        $data['list'][$slug['category_id']]['image'] = 'https://non-lethal-applications.com/images/nla/kb/genlocked-playback/genlocked-playback-poster.jpg';
                    }
                }
            }
        }else{
            foreach($pages as $slug){
                foreach ($merge as $cat_slug){
                    if($slug['category_id'] == $cat_slug['id']) {
                        if( $cat_slug['slug'] == $subcategory) {
                            $data['sub_title_page'] = ucfirst($cat_slug['title']);
                            $data['list'][$slug['category_id']]['url'] = '/' . $array_cats_head[0]['slug'] . '/' . $array_cats_head[1]['slug'] . '/' . $cat_slug['slug'] . '/' . $slug['slug'];
                            $data['list'][$slug['category_id']]['title'] = $slug['title'];
                            $data['list'][$slug['category_id']]['content'] = self::cut_by_words(400,strip_tags($slug['content']));
                            //$data['list'][$slug['category_id']]['cat_name'] = $cat_slug['title'];
                            $data['list'][$slug['category_id']]['image'] = 'https://non-lethal-applications.com/images/nla/kb/genlocked-playback/genlocked-playback-poster.jpg';
                        }
                    }
                }
            }

        }
        $data['all_item_url'] = '/'.$array_cats_head[0]['slug'].'/'.$array_cats_head[1]['slug'];

        foreach($cats->toArray() as $key=>$cat){
            $data['categories'][$key]['url'] = '/'.$array_cats_head[0]['slug'].'/'.$array_cats_head[1]['slug'].'/'.$cat['slug'];
            $data['categories'][$key]['name'] = ucfirst($cat['title']);
        }

        return $data;
    }

    public static function getItem($headcategory,$category,$subcategory,$item){
        $page = Pages::whereSlug($item)->whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get()->pluck('content')->toArray();

        return $page;
    }

    //crop text by word
    public static  function cut_by_words($maxlen, $text) {
        $len = (mb_strlen($text) > $maxlen)? mb_strripos(mb_substr($text, 0, $maxlen), ' ') : $maxlen;
        $cutStr = mb_substr($text, 0, $len);
        $temp = (mb_strlen($text) > $maxlen)? $cutStr. '...' : $cutStr;
        return $temp;
    }
    //crop text by word

}
