<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Models\Front\Contents\Categories;

class Pages extends Model
{
    public static function getPage($slug){

        $content = Pages::whereSlug("$slug")->where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))->select('content')->get()->toArray();

       // dd($content);

       return $content[0]['content'];
    }

    public static function getCatgoryPage($category,$subcategory){

        $data = [];

        $cats_head = Categories::whereId(DB::raw('(select main_category from categories where slug = "'.$subcategory.'" and lang_id = (select id from languages where locale = "'.App::getLocale().'"))'))
        ->orWhere('slug',$subcategory)
        ->where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))
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

        //var_dump($cats_head->toArray());
        //var_dump($cats->toArray());
        //var_dump($pages);

       $array_cats_head = $cats_head->toArray();



        foreach($pages as $slug){
            foreach ($merge as $cat_slug){
                if($slug['category_id'] == $cat_slug['id']) {
                    $data['list'][$slug['category_id']]['url'] = $array_cats_head[0]['slug'].'/'.$array_cats_head[1]['slug'].'/'.$cat_slug['slug'].'/'.$slug['slug'];
                    $data['list'][$slug['category_id']]['title'] = $slug['title'];
                    $data['list'][$slug['category_id']]['content'] = $slug['content'];
                    $data['list'][$slug['category_id']]['cat_name'] = $cat_slug['title'];
                    $data['list'][$slug['category_id']]['image'] = 'image';
                }
            }
        }

        foreach($cats->toArray() as $key=>$cat){
            $data['categories'][$key]['url'] = $array_cats_head[0]['slug'].'/'.$cat['title'];
            $data['categories'][$key]['name'] = $cat['title'];
        }

        return $data;
    }

}
