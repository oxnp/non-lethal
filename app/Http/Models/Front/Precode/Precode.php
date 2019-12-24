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

    /**
     * Marks a precode as used
     *
     * @param $precode      string      The precode
     *
     * @return boolean        true on success, false else
     */
    public static function consumeCode($precode) {

        // Get unformatted string
        $precode = str_replace('-','',$precode);

        // Set dataset as used
        $result = Precode::where('precode',$precode)->update(['used' => 1]);

        return $result;
    }
}
