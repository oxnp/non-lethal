<?php

namespace App\Http\Models\License;

use App\Http\Models\Helper\Helper;
use App\Http\Models\Precode\Precode;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Products\Products;
use DB;
use Illuminate\Database\Query\Builder;
use App\Http\Models\License\Seats;

class License extends Model
{
    protected $table="licenses";
    protected $fillable = ['buyer_id','serial','product_id','ilok_code','date_activate','max_majver','seats','prod_features','paddle_oid','notes','support_days','license_days','date_purchase','type'];
    private static $allSerials = null;

    /**
     * Get Licenses
     * @param   Request  $request
     * @return collection
     */
    public static function getLicenses($request){
        $per_page = 20;
        if ($request->per_page != null){
            $per_page = $request->per_page;
        }

        $query = License::select('licenses.*','b.last', 'b.first', 'b.email', 'b.company', DB::raw('b.notes AS buyer_notes'),'p.name', 'p.code', 'p.features',DB::raw('count(s.id) as count_seats'))
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->leftjoin('products as p','p.id','licenses.product_id')
            ->leftjoin('seats as s','s.license_id','licenses.id')->groupby('licenses.id');

        $filter = array();
        $filter['sort'] = 'desc';
        $filter['orderby'] = 'licenses.id';
        $filter['search_string'] = '';

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

        if($request->searchstring){
            $filter['search_string'] = $request->searchstring;
            $query->whereLike(['b.last','b.first','licenses.serial','licenses.ilok_code',DB::raw("CONCAT(b.last,' ',b.first)")],str_replace('-','',$filter['search_string']));
        }
        $url = '';

        foreach($filter as $f=>$value){
            $url .= $f.'&'.$value;
        }
        $result = array();

        $result['licenses'] = $query->paginate($per_page);
        $result['filter'] = $filter;
        return $result;
    }
    /**
     * Get License By ID
     * @param   int  $id
     * @return collection
     */
    public static function getLicense($id){
        $license = License::select('licenses.*',DB::raw('count(s.id) as count_seats'),'b.last', 'b.first', 'b.email', 'b.company', DB::raw('b.notes AS buyer_notes'),'p.name', 'p.code', 'p.features')
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->leftjoin('products as p','p.id','licenses.product_id')
            ->leftjoin('seats as s','s.license_id','licenses.id')->where('licenses.id',$id)->groupby('licenses.id')->get()->toArray();

        return $license;
    }
    /**
     * Update License By ID
     * @param   Request $request, int $id
     * @return bool
     */
    public static function updateLicense($request,$id){

        if (isset($request->remove)){
            foreach($request->remove as $seat){
                Seats::find($seat)->delete();
            }
        }

        License::find($id)->update([
            'product_id'=>$request->product_id,
            'serial'=> isset($request->serial) ? str_replace('-','',$request->serial) : '',
            'ilok_code'=> isset($request->ilok_code) ? $request->ilok_code : '',
            'date_activate'=>$request->date_activate,
            'max_majver'=>$request->max_majver,
            'seats'=>$request->seats,
            'prod_features'=> isset($request->prod_features) && $request->prod_features == 'on' ? 1 : 0,
            'paddle_oid'=>$request->paddle_oid,
            'notes'=>$request->notes,
            'support_days'=>$request->support_days,
            'license_days'=>$request->license_days,
            'date_purchase'=>$request->date_purchase,
            'type'=>$request->license_type,
        ]);

        return true;
    }


    public static  function addLicense($request){


        $license_for_buyer = License::create([
            'product_id'=>$request->product_id,
            'buyer_id'=>$request->buyer_id,
            'serial'=> ($request->serial == null) ? str_replace('-','',License::generateSerialNumber()) : str_replace('-','',$request->serial),
            'max_majver'=>$request->max_majver,
            'seats'=>$request->seats,
            'notes'=>$request->notes,
            'support_days'=>$request->support_days,
            'license_days'=>$request->license_days,
            'date_purchase'=>$request->date_purchase,
            'type'=>$request->license_type
        ]);
        return $license_for_buyer;
    }

    /**
     * Get seats By License ID
     * @param   int  $id
     * @return collection
     */
    public static function getSeatsToLicense($id){
        $seats = Seats::where('license_id',$id)->get();
        return $seats;
    }

    /**
     * Generate serial
     * @param   bool $preCodeMode,  string $serialPrefix, int $checkSumModule, int $serialLen
     * @return string
     */
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
    /**
     * Test Serial
     * @param  string $serial
     * @return bool
     */
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
    /**
     * Generate precode
     * @param  string $prefix
     * @return string
     */
    public static function generatePreCode($prefix = '')
    {
        return self::generateSerialNumber(true, $prefix, 47);
    }
    /**
     * Transfer Licenses
     * @param  array $licenses_ids, int $buyer_id
     * @return bool
     */
    public static function transferLicense($licenses_ids, $buyer_id){
    foreach($licenses_ids as $license_id) {
       License::find($license_id)->update([
            'buyer_id' => $buyer_id
        ]);
    }
        return true;
    }

    /**
     * Method to get a license matching an iLok code and an optional featureset
     *
     * @param $productID
     * @param $buyerID
     * @return mixed        The license data, false if not found
     */
    public static function lookupByilokCode($iLokCode, $features = array())
    {

        $query = License::where(DB::raw("BINARY licenses.ilok_code ='".$iLokCode."'"))
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->leftjoin('products as p','p.id','licenses.product_id')->get();

        if(!empty($features)) {
            $query->where('licenses.prod_features=' . Helper::feature2bitmask($features));
        }

        return $query->get()->toArray();
    }

    /**
     * Method to get a license matching a serial and an optional featureset
     *
     * @param $productID
     * @param $buyerID
     * @return mixed        The license data, false if not found
     */
    public static function lookupBySerial($serial, $features = array())
    {
        // Clean serial
        $serial = str_replace('-', '',trim($serial));

        $query = License::where(DB::raw("BINARY licenses.serial ='".$serial."'"))
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->leftjoin('products as p','p.id','licenses.product_id')->get();

        if(!empty($features)) {
            $query->where('licenses.prod_features=' . Helper::feature2bitmask($features));
        }

        return $query->get()->toArray();
    }

    /**
     * License lookup for old appactivation license by serial
     *
     * @param $serial
     */
    public static function lookupOldSerial($serial) {

        $query = License::where(DB::raw("BINARY licenses.serial ='".$serial."'"))->get()->toArray();

        return $query;
    }

    //frontend
    /**
     * Deletes old component license by license id
     *
     * @param $licenseID
     *
     * @return mixed
     */
    public static function deleteOldLicense($licenseID) {

        // Check if old component is installed
       // if (!JComponentHelper::isInstalled('com_appactivation'))
            return false;

     /*   $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__appactivation_licenses');
        $query->where('id=' . $licenseID);

        $db->setQuery($query);
        return $db->execute();
     */
    }

    /**
     * Function to load product data matching a given Paddle Subscription ID
     *
     * @param $pPID     int     The paddle SID
     *
     * @return mixed        The product data as object
     */
    public static function lookupByPaddleSID($pSID) {

        $result = License::wherePaddleSid($pSID)->get();
        return $result;
    }

    /**
     * Function to load product data matching a given Paddle Subscription ID
     *
     * @param $pPID     int     The paddle SID
     *
     * @return mixed        The product data as object
     */
    public static function lookupByPaddleOID($pOID) {

        $result = License::wherePaddleOid($pOID)->get();
        return $result;
    }
}
