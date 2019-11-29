<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Front\Contents\Pages;
use Session;

class PageController extends Controller
{
    public function page($slug){
        $content = Pages::getPage($slug);
        return view('Front.page')->with(['content'=>$content]);
    }

    public function category($headcategory,$category){
        $data= Pages::getCategoryPage($headcategory,$category);
        return view('Front.page_category')->with(['data'=>$data]);
    }

    public function subcategory($headcategory,$category,$subcategory){
            $data = Pages::getCategoryPage($headcategory,$category,$subcategory);
             return view('Front.page_category')->with(['data'=>$data]);
    }
    public function item($headcategory,$category,$subcategory,$item){
            $data = Pages::getItem($headcategory,$category,$subcategory,$item);
            dd($data);

             return view('Front.page_item')->with(['data'=>$data]);
    }
}
