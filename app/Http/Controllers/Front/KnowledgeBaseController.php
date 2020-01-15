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
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/support/'.$category;
        $breadcrumbs[0]['text'] = trans('main.knowledge_base');
        $data= KnowledgeBase::getCategoryPage($headcategory,$category);
        $categories = ProductsPageCategory::getCategoriesTolist();

        return view('Front.knowledge_base_category')->with([
            'data'=>$data,
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function subcategory($headcategory,$category,$subcategory){

        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/support/'.$category;
        $breadcrumbs[0]['text'] = trans('main.knowledge_base');
        $data = KnowledgeBase::getCategoryPage($headcategory,$category,$subcategory);
        $breadcrumbs[1]['url'] = '/support/'.$category.'/'.$subcategory;
        $breadcrumbs[1]['text'] = $data['sub_title_page'];

        $categories = ProductsPageCategory::getCategoriesTolist();

         return view('Front.knowledge_base_category')->with([
             'data'=>$data,
             'categories'=>$categories,
             'breadcrumbs' => $breadcrumbs
         ]);
    }
    public function item($headcategory,$category,$subcategory,$item){

        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/support/'.$category;
        $breadcrumbs[0]['text'] = trans('main.knowledge_base');

        $data = KnowledgeBase::getCategoryPage($headcategory,$category,$subcategory);
        $breadcrumbs[1]['url'] = '/support/'.$category.'/'.$subcategory;
        $breadcrumbs[1]['text'] = $data['sub_title_page'];


        $categories = ProductsPageCategory::getCategoriesTolist();
        $content = KnowledgeBase::getItem($headcategory,$category,$subcategory,$item);

        $breadcrumbs[2]['url'] = '/support/'.$category.'/'.$subcategory.'/'.$item;
        $breadcrumbs[2]['text'] = $content[0]['title'];

         return view('Front.knowledge_base_item')->with([
             'content'=>$content[0]['content'],
             'categories'=>$categories,
             'breadcrumbs' => $breadcrumbs
         ]);
    }
}
