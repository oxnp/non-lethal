<?php

namespace App\Http\Models\License;

use App\Http\Models\Precode\Precode;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Products\Products;
use DB;
use Illuminate\Database\Query\Builder;

class License extends Model
{
    protected $table="licenses";
    private static $allSerials = null;

    public static function getLicenses($request){

        $per_page = 20;
        if ($request->per_page != null){
            $per_page = $request->per_page;
        }

        $query = License::select('licenses.*','b.last', 'b.first', 'b.email', 'b.company', DB::raw('b.notes AS buyer_notes'),'p.name', 'p.code', 'p.features')
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->leftjoin('products as p','p.id','licenses.product_id');

        $filter = array();
        $filter['sort'] = 'desc';
        $filter['orderby'] = 'licenses.id';
        $filter['searcstring'] = '';



        if($request->orderby){
            $filter['orderby'] = $request->orderby;
            if ($request->sort){
                $filter['sort'] = $request->sort;
                $query->orderby($filter['orderby'],$filter['sort']);
            }

        }

        Builder::macro('whereLike', function($attributes, string $searchTerm) {
            foreach(array_wrap($attributes) as $attribute) {
                $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
            }

            return $this;
        });

        if($request->searcstring){
            $filter['searcstring'] = $request->searcstring;
            $query->whereLike(['b.last','b.first','licenses.serial'],str_replace('-','',$filter['searcstring']));
        }
        $url = '';
        foreach($filter as $filter=>$value){
            $url .= $filter.'&'.$value;
        }

        $licenses = $query->paginate($per_page);
        return $licenses;
    }


    public static function generateSerialNumber($preCodeMode = false, $serialPrefix = '', $checkSumModule = 53, $serialLen = 20)
    {
        $checkSumMultiplier = 7;
        $validChars = "123456789abcdefghijklmnpqrstuvwxyzACDEFGHIJKLMNPQRSTUVWXYZ";
        $outputString = "";
        $sum = 0;

        $i = 0;     // Init checksum digit counter
        if(strlen($serialPrefix) == 5) {
            $outputString = $serialPrefix;
            $i = 5;  // Set checksum digit counter start after prefix (5th digit)
        }

        for ($i; $i < $serialLen-1; $i++)
        {
            if (!($i % 5) && ($i!=0))
                $outputString .= "-";

            $rnd = rand(0, strlen($validChars)-1);
            $outputString .= $validChars[$rnd];

            if (($i%2) == 0)
                $sum = $sum + $rnd;
            else
                $sum = $sum + ($rnd*$checkSumMultiplier);
        }

        $sum = $sum % $checkSumModule;
        $outputString .= $validChars[$checkSumModule-$sum];

        // Test the created serial to avoid duplicates
        if($preCodeMode) {
            // Get pre-code model to test precode

            if(!Precode::testPreActivationCode($outputString)) {
                self::generateSerialNumber($preCodeMode, $serialPrefix, $checkSumModule, $serialLen);
            }
        } else {
            if(!self::testSerial($outputString)) {

                self::generateSerialNumber($preCodeMode, $serialPrefix, $checkSumModule, $serialLen);
            }
        }

        return $outputString;
    }

    public static function testSerial($serial) {
        // Clean serial
        $serial = trim(str_replace('-','',$serial));

        // Retrieve all serials in database
        if(!self::$allSerials) {
            $all_serials = License::pluck('serial')->toArray();
            self::$allSerials = $all_serials;
        }

        // look for serial in serial list
        // if found, serial not valid, return false
        // if not found, add it dynamically to the serial list
        if(in_array($serial, self::$allSerials)) {
            return false;
        } else {
            self::$allSerials[] = $serial;
        }

        return true;
    }

    public static function generatePreCode($prefix = '')
    {
        return self::generateSerialNumber(true, $prefix, 47);
    }
}
