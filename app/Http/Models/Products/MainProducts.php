<?php

namespace App\Http\Models\Products;

use Illuminate\Database\Eloquent\Model;

class MainProducts extends Model
{
    protected $casts = [
        'id' => 'string',
    ];

    public static function getMainProducts(){
        $mainproducts = MainProducts::all();
        return $mainproducts;
    }
}
