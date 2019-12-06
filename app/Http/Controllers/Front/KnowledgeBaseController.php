<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Contents\ProductsPageCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Front\Contents\KnowledgeBase;
use Session;

class KnowledgeBaseController extends Controller
{

    public function category($headcategory,$category){
        $data= KnowledgeBase::getCategoryPage($headcategory,$category);
        $categories = ProductsPageCategory::getCategoriesTolist();

        return view('Front.knowledge_base_category')->with([
            'data'=>$data,
            'categories'=>$categories
        ]);
    }

    public function subcategory($headcategory,$category,$subcategory){
        $data = KnowledgeBase::getCategoryPage($headcategory,$category,$subcategory);
        $categories = ProductsPageCategory::getCategoriesTolist();
       // dd($data);
         return view('Front.knowledge_base_category')->with([
             'data'=>$data,
             'categories'=>$categories
         ]);
    }
    public function item($headcategory,$category,$subcategory,$item){
        $categories = ProductsPageCategory::getCategoriesTolist();
        $content = KnowledgeBase::getItem($headcategory,$category,$subcategory,$item);
         return view('Front.knowledge_base_item')->with([
             'content'=>$content[0],
             'categories'=>$categories
         ]);
    }
}
