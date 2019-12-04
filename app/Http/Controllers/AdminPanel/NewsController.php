<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Contents\News;
use App\Http\Models\Front\Contents\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::getNews();
        return view('AdminPanel.contents.news_list')->with([
            'news' => $news
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
        $next_id = News::getLastid();
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/news/' . $next_id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/news/' . $next_id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        News::addNews($request,$storage_image);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::getNewsById($id);
        $langs = Languages::all();
        return view('AdminPanel.contents.news_show')->with([
            'news' => $news,
            'langs' => $langs,
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
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/news/' . $id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/news/' . $id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        News::updateNews($request,$storage_image);
        return redirect()->back();
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
