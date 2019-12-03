<?php

namespace App\Http\Models\Front\Contents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class StaticPages extends Model
{
    protected $table = "static_page";
    public static function getPage($slug){

        $content = StaticPages::whereSlug("$slug")->where('lang_id',DB::raw('(select id from languages where locale = "'.App::getLocale().'")'))->get()->toArray();

        return $content;
    }
}
