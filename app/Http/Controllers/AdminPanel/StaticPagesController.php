<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Contents\StaticPages;
use App\Http\Models\Front\Contents\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class StaticPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $static_pages = StaticPages::getStaticPages();

        return view('AdminPanel.contents.static_page_list')->with(['static_pages'=>$static_pages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langs = Languages::all();
        return view('AdminPanel.contents.static_page_add')->with(['langs'=>$langs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $next_id = StaticPages::getLastid();
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/static-pages/' . $next_id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/static-pages/' . $next_id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        StaticPages::addPage($request,$storage_image);
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
        $static_page = StaticPages::getPage($id);
        $langs = Languages::all();
        return view('AdminPanel.contents.static_page_show')->with([
            'static_page'=>$static_page,
            'langs'=>$langs
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
            $storage = $file->store('image/static-pages/' . $id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/static-pages/' . $id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }
        StaticPages::updatePage($request,$storage_image);

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
