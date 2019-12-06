<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class KnowledgeBase extends Model
{
    public $timestamps = false;
    protected $table = 'knowledge_base';

    public static function getKnowledgeBases(){
        $knowledge_bases= KnowledgeBase::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->get();
        return $knowledge_bases;
    }

    public static function getKnowledgeBase($id){
        $knowledge_base = KnowledgeBase::find($id);
        $knowledge_base_item = KnowledgeBase::whereSlug($knowledge_base->slug)->get();
        return $knowledge_base_item;
    }

    public static function updateKnowledgeBase($request,$storage_image){
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
            ProductsPage::find($id)->update($data[$id]);
            if($storage_image != ''){
                ProductsPage::find($id)->update(['image'=>$storage_image]);
            }
        }
        return true;
    }

    public static function addKnowledgeBase($request,$storage_image){
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
            ProductsPage::find($id)->update($data[$id]);
            if($storage_image != ''){
                ProductsPage::find($id)->update(['image'=>$storage_image]);
            }
        }
        return true;
    }

    public static function getLastid(){
        $last_id = ProductsPage::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }
}
