<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\Contents\StaticPages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function page($slug){
        $content = StaticPages::getPage($slug);
        if(empty($content)){
            return abort(404);
        }
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/'.$slug;
        $breadcrumbs[0]['text'] = $content[0]['title'];



        $categories = ProductsPageCategory::getCategoriesTolist();
        return view('Front.static_page')->with([
            'content'=>$content,
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
