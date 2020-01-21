<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Models\Front\Contents\KnowledgeBaseCategories;

class KnowledgeBase extends Model
{
    protected $table = 'knowledge_base';


    public static function getCategoryPage($headcategory,$category,$subcategory = ''){

        $data = [];
        $data['list'] = [];
        $cats_head = KnowledgeBaseCategories::whereId(DB::raw('(select main_category from knowledge_base_categories where slug = "' . $category . '" and lang_id = (select id from languages where locale = "' . App::getLocale() . '"))'))
            ->orWhere('slug', $category)
            ->where('lang_id', DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))
            ->get();

        $filtered = $cats_head->filter(function ($item) {
            if($item['main_category'] != 0) {
                return $item;
            }
        });

        $data['title_page'] = $filtered[1]->title;

        $cats = KnowledgeBaseCategories::whereMainCategory($filtered[1]->id)->get();
        $merge = $cats_head->merge($cats)->toArray();




        $pages = KnowledgeBase::whereIn('category_id',$cats->pluck('id')->toArray())->get()->toArray();



        $array_cats_head = $cats_head->toArray();

        if ($subcategory == '') {
            foreach ($pages as $page) {

                foreach ($merge as $cat_slug) {
                    if ($page['category_id'] == $cat_slug['id']) {
                        $data['list'][$page['id']]['url'] = '/' . $array_cats_head[0]['slug'] . '/' . $array_cats_head[1]['slug'] . '/' . $cat_slug['slug'] . '/' . $page['slug'];
                        $data['list'][$page['id']]['title'] = $page['title'];
                        $data['list'][$page['id']]['content'] = self::cut_by_words(400,strip_tags( $page['short_text'] ? $page['short_text'] : $page['content']));
                        //$data['list'][$slug['category_id']]['cat_name'] = $cat_slug['title'];
                        $data['list'][$page['id']]['image'] = $page['image'];
                    }
                }
            }
        }else{
            foreach($pages as $page){
                foreach ($merge as $cat_slug){
                    if($page['category_id'] == $cat_slug['id']) {
                        if( $cat_slug['slug'] == $subcategory) {
                       //     var_dump($page['title']." - ".$page['category_id'].' - '.$cat_slug['id']."\n");
                            $data['sub_title_page'] = ucfirst($cat_slug['title']);
                            $data['list'][$page['id']]['url'] = '/' . $array_cats_head[0]['slug'] . '/' . $array_cats_head[1]['slug'] . '/' . $cat_slug['slug'] . '/' . $page['slug'];
                            $data['list'][$page['id']]['title'] = $page['title'];
                            $data['list'][$page['id']]['content'] = self::cut_by_words(400,strip_tags( $page['short_text'] ? $page['short_text'] : $page['content']));
                            //$data['list'][$slug['category_id']]['cat_name'] = $cat_slug['title'];
                            $data['list'][$page['id']]['image'] = $page['image'];
                        }
                    }
                }
            }
//dd($data);
        }
        $data['all_item_url'] = '/'.$array_cats_head[0]['slug'].'/'.$array_cats_head[1]['slug'];

        foreach($cats->toArray() as $key=>$cat){
            $data['categories'][$key]['url'] = '/'.$array_cats_head[0]['slug'].'/'.$array_cats_head[1]['slug'].'/'.$cat['slug'];
            $data['categories'][$key]['name'] = ucfirst($cat['title']);
            $data['categories'][$key]['visible'] = $cat['visible'];
        }

        return $data;
    }

    public static function getItem($headcategory,$category,$subcategory,$item){
        $page = KnowledgeBase::whereSlug($item)->whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get()->toArray();

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
