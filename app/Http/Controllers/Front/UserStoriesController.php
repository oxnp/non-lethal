<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\Contents\UserStories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserStoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/'.env('USER_STORIES_URL');
        $breadcrumbs[0]['text'] = trans('main.USER_STORIES');

        $user_stories = UserStories::getStories();
        $categories = ProductsPageCategory::getCategoriesTolist();
        return view('Front.user_stories')->with([
            'user_stories'=>$user_stories,
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs,
            'meta_title' =>  trans('main.USER_STORIES')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
    public function show($slug)
    {

        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/'.env('USER_STORIES_URL');
        $breadcrumbs[0]['text'] = trans('main.USER_STORIES');

        $user_story = UserStories::getStory($slug);

        $breadcrumbs[1]['url'] = '/'.env('USER_STORIES_URL').'/'.$slug;
        $breadcrumbs[1]['text'] = $user_story[0]->title;


        $categories = ProductsPageCategory::getCategoriesTolist();
        return view('Front.story')->with([
            'user_story'=>$user_story,
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs,
            'meta_title' =>  $user_story[0]->title
        ]);
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
