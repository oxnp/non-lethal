<?php

namespace App\Http\Models\IlokCodes;

use Illuminate\Database\Eloquent\Model;

class IlokCodes extends Model
{
    protected $table = 'ilokcodes';
    public static function getListIlokCodes($per_page){
        $ilokcodes =  IlokCodes::select('products.code','products.name','ilokcodes.id', 'ilokcodes.ilok_code','ilokcodes.batchtime','ilokcodes.used')
            ->leftjoin('products','products.id','ilokcodes.product_id')
            ->groupby('ilokcodes.id')
            ->paginate($per_page);
        return $ilokcodes;
    }

    public static function remove($ids){

        IlokCodes::whereIn('id',$ids)->delete();
        return true;
    }

}
