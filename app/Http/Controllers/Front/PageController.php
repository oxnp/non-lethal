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
}
