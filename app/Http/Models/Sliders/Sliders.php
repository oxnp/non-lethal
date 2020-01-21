<?php

namespace App\Http\Models\Sliders;

use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    protected $table = 'sliders';
    public $timestamps = false;

    public static function getLastid(){
        $last_id = Sliders::select(DB::raw('max(id) as last_id'))->pluck('last_id')->toArray();
        return $last_id[0] + 1;
    }

    public static function getAllSlides(){
        $slides = Sliders::all();

        return $slides;
    }

    public static function getSlide($id){
        $slide = Sliders::where('id',$id)->get();

        return $slide;
    }

    public static function updateSlide($request, $storage_image,$id){
        $data = $request->all();

        unset($data['_token']);
        unset($data['_method']);
        if ($storage_image != ''){
            $data['image'] = $storage_image;
        }
        Sliders::find($id)->update($data);
    }

    public static function storeSlide($request,$storage_image){
        $data = $request->all();

        unset($data['_token']);
        if ($storage_image != ''){
            $data['image'] = $storage_image;
        }
        Sliders::create($data);
    }
}
