<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Front\Contents\KnowledgeBase;
use Session;

class KnowledgeBaseController extends Controller
{

    public function category($headcategory,$category){
        $data= KnowledgeBase::getCategoryPage($headcategory,$category);
        return view('Front.knowledge_base_category')->with(['data'=>$data]);
    }

    public function subcategory($headcategory,$category,$subcategory){
            $data = KnowledgeBase::getCategoryPage($headcategory,$category,$subcategory);
             return view('Front.knowledge_base_category')->with(['data'=>$data]);
    }
    public function item($headcategory,$category,$subcategory,$item){
            $content = KnowledgeBase::getItem($headcategory,$category,$subcategory,$item);
             return view('Front.knowledge_base_item')->with(['content'=>$content[0]]);
    }
}
