<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Session;
class Pages extends Model
{
    public static function getPage($slug){

        $content = Pages::whereSlug("$slug")->where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))->select('content')->get()->toArray();

       // dd($content);

       return $content[0]['content'];
    }
}
