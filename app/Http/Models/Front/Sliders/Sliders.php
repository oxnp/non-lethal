<?php

namespace App\Http\Models\Front\Sliders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Sliders extends Model
{
    protected $table = 'sliders';
    public $timestamps = false;
    protected $fillable = ['title','sub_title','link','lang_id','image'];
    public static function getSlides(){
        $slides= Sliders::whereLangId(DB::raw('(select id from languages where locale = "' . App::getLocale() . '")'))->paginate(9);

        return $slides;
    }
}
