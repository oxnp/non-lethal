<?php

namespace App\Http\Models\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class KnowledgeBaseCategories extends Model
{
    public $timestamps = false;
    protected $table = 'knowledge_base_categories';

    public static function getKnowledgeBaseCategories(){
        $knowledge_list = KnowledgeBaseCategories::where('main_category','<>','null')->get();

        return $knowledge_list;
    }

    public static function getKnowledgeBaseCategoriesTolist(){
        $knowledge_list = KnowledgeBaseCategories::where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))->get();
        return $knowledge_list;
    }

    public static function getCKnowledgeBaseCategory($id){
        $knowledge_base = KnowledgeBaseCategories::find($id);
        $knowledge_base_page = KnowledgeBaseCategories::whereSlug($knowledge_base->slug)->get();
        return $knowledge_base_page;
    }

    public static function updateCategory($request){

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
            KnowledgeBaseCategories::find($id)->update($data[$id]);
        }
    }

}
