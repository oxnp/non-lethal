<?php

namespace App\Http\Models\IlokCodes;

use Illuminate\Database\Eloquent\Model;

class IlokCodes extends Model
{
    protected $table = 'ilokcodes';

    /**
     * Get IlokCodes
     * @param   array  $per_page
     * @return collection
     */
    public static function getListIlokCodes($per_page){
        $ilokcodes =  IlokCodes::select('products.code','products.name','ilokcodes.id', 'ilokcodes.ilok_code','ilokcodes.batchtime','ilokcodes.used')
            ->leftjoin('products','products.id','ilokcodes.product_id')
            ->groupby('ilokcodes.id')
            ->paginate($per_page);
        return $ilokcodes;
    }
    /**
     * Remove IlokCodes
     * @param   array  $ids
     * @return bool
     */
    public static function remove($ids){

        IlokCodes::whereIn('id',$ids)->delete();
        return true;
    }
    /**
     * Insert IlokCodes
     * @param   array  $data
     * @return bool
     */
    public static function import($data){
        IlokCodes::insert($data);
        return true;
    }

}
