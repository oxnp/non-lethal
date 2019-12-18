<?php

namespace App\Http\Models\Front\Precode;

use Illuminate\Database\Eloquent\Model;

class Precode extends Model
{
    protected $table = 'precodes';

    public static function getData($precode) {

        // Get unformatted string
        // $precode = JAppActivationHelper::cleanFormattedString($precode, '-');

        // Set dataset as used
        $result = Precode::wherePrecode($precode)->get()->toArray();

       // dd($result);

        return $result;
    }
}
