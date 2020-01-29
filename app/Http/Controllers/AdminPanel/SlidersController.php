<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Front\Contents\Languages;
use App\Http\Models\Sliders\Sliders;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SlidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Sliders::getAllSlides();

        return view('AdminPanel.sliders.slides-list')->with(['slides'=>$slides]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langs = Languages::all();
        return view('AdminPanel.sliders.slides-add')->with([
            'langs'=>$langs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $next_id = Sliders::getLastid();
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/slides/' . $next_id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/slides/' . $next_id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }
        Sliders::storeSlide($request,$storage_image,$request->link);
        return  redirect(route('sliders.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slide = Sliders::getSlide($id);
        $langs = Languages::all();
        return view('AdminPanel.sliders.slides-show')->with([
            'slide'=>$slide,
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
            $storage = $file->store('image/slides/' . $id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/slides/' . $id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }


        Sliders::updateSlide($request,$storage_image,$request->link);
        if ($request->redirect != 0){
            return redirect(route('sliders.index'));
        }else{
            return redirect(route('sliders.show',$id));
        }
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
