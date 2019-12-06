<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class KnowledgeBase extends Model
{
    public $timestamps = false;
    protected $table = 'knowledge_base';
    protected $fillable = ['category_id','lang_id','content','title','slug','image','short_text'];

    public static function getKnowledgeBases(){
        $knowledge_bases= KnowledgeBase::where('knowledge_base.lang_id',DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))
            ->leftjoin('knowledge_base_categories','knowledge_base_categories.id','knowledge_base.category_id')
            ->select(DB::raw('knowledge_base_categories.title as name_category'),'knowledge_base.title','knowledge_base.id')
            ->get();
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
            KnowledgeBase::find($id)->update($data[$id]);
            if($storage_image != ''){
                KnowledgeBase::find($id)->update(['image'=>$storage_image]);
            }
        }
        return true;
    }

    public static function addKnowledgeBase($request,$storage_image){

        $data = array();
        foreach($request->all() as $key=>$value){
            if (is_array($value)){
                foreach($value as $lang_id=>$val){
                    $data[$lang_id][$key] = $val;
                    $data[$lang_id]['lang_id'] = $lang_id;
                    if($storage_image != ''){
                        $data[$lang_id]['image'] = $storage_image;
                    }
                }
            }
        }

        KnowledgeBase::insert($data);
        return true;
    }

    public static function getLastid(){
        $last_id = ProductsPage::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }
}
