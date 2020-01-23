<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Contents\News;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\Contents\UserStories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Front\Sliders\Sliders;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::getNews();
        $slides = Sliders::getSlides();
        $user_stories = UserStories::getStories();
        $categories = ProductsPageCategory::getCategoriesTolist();
        $meta_title = trans('main.homepagetitle');
        return view('Front.homepage')->with([
            'categories'=>$categories,
            'news'=>$news,
            'user_stories'=>$user_stories,
            'slides'=>$slides,
            'meta_title'=> $meta_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
