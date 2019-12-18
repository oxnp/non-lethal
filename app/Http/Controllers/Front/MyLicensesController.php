<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Buyers\Buyers;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\MyLicenses\MyLicenses;
use App\Http\Models\Front\Precode\Precode;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyLicensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ProductsPageCategory::getCategoriesTolist();
        $licenses = MyLicenses::getLicensesByUser();

        return view('Front.my_licenses')->with([
            'categories' => $categories,
            'licenses'=>$licenses
        ]);
    }

    public function fulfillment(Request $request)
    {
        $precode = str_replace('-','',$request->code);
        // Check security token on manual activation requests
        $UserID = $request->user_id;
        if ($UserID != Auth::ID())
        {
            //JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
            return 'attemp';
        }
        $productData = MyLicenses::lookupByPreCode($precode);
        if ($productData == false){
            return 'Code already consumed';
        }

        // Get pre-activation type
        $precodeData = Precode::getData($precode);
        $activationMode = intval($precodeData[0]['type']);

        // If feature activation, check if prefix is set in product settings
        if($activationMode == env('JAA_PRE_ACTIVATION_FEATURE')) {

            $codePrefix = substr($precode, 0, 5);
            if(strpos($productData[0]['feature_prefixes'], $codePrefix) === false) {
                //JAppActivationHelper::log('Provided feature pre-code does not match any prefix!', JLog::NOTICE);
                //$this->_finishFulfillment();
                //throw new Exception('Provided pre-code invalid', 403);
            }
        }

        // Serial or iLok code delivered for upgrade or feature activation?
        if ($activationMode == env('JAA_PRE_ACTIVATION_UPGRADE') || $activationMode ==  env('JAA_PRE_ACTIVATION_FEATURE')) {

            $upgradeSerial = $request->serial;
            $upgradeiLokCode = $this->ilok;

            if(empty($upgradeSerial) && empty($upgradeiLokCode)) {
                //JAppActivationHelper::log('No serial or iLok code for upgrade provided!', JLog::NOTICE);
                //$this->_finishFulfillment();
                //throw new Exception('No serial provided', 403);
                return 'No serial provided';
            }
        }

        // Full activation mode handler


        $postData = $request->all();
        if($activationMode == env('JAA_PRE_ACTIVATION_FULL') || $activationMode == env('JAA_PRE_ACTIVATION_TEMP')) {
            $buyerFields = array_flip(array('last', 'first', 'company', 'street1', 'street2', 'zip', 'city', 'country', 'state', 'email', 'phone', 'website'));
            $buyerData = array_intersect_key($postData, $buyerFields);

            // Verify important buyer data
            if(!isset($buyerData['email']) || empty($buyerData['email'])) {
                //JAppActivationHelper::log('Buyer email empty or not set!', JLog::NOTICE);
               // $this->_finishFulfillment();
                return 'Buyer email missing';
            }

            // Check if buyer already exists, if not create new
            $buyer = Buyers::buyerLookupByMail($buyerData['email']);

            if(!empty($buyer))
            {
                $buyerID = $buyer[0]['id'];     // Get existing buyer's ID

                //JAppActivationHelper::log('Buyer already in database, ID = ' . $buyer->id, JLog::INFO);
            }
            else {

                // Try to get buyer data from joomla profile (On manual pre-code redemption)
                if ($UserID) {
                    $userData =  User::find($UserID)->toArray();
                    $joomlaProfile = Buyers::whereUserId($userData['id'])->get()->toArray();
                    $buyerData = array_merge($joomlaProfile, $buyerData);
                    $buyerData['user_id'] = intval($UserID);
                }


                if(!isset($buyerData['first'])) {
                    $buyerData['first'] = $userData['name'];
                }

                if(!isset($buyerData['last'])) {
                    $buyerData['last'] = $userData['name'];
                }


                if(!isset($buyerData['last']) || empty($buyerData['last'])) {
                   // JAppActivationHelper::log('Buyer lastname empty or not set!', JLog::NOTICE);
                   // $this->_finishFulfillment();
                    return 'Buyer lastname missing';
                }
                if(!isset($buyerData['first']) || empty($buyerData['first'])) {
                    //JAppActivationHelper::log('Buyer firstname empty or not set!', JLog::NOTICE);
                    //$this->_finishFulfillment();
                    return 'Buyer firstname missing';
                }
                // Create new buyer
                $buyerCreated = Buyers::create($buyerData);

                if(!$buyerCreated)
                {
                    //JAppActivationHelper::log('Error adding new buyer', JLog::ERROR);
                    //$this->_finishFulfillment();
                    return 'Component error';
                }

                $buyerID = $buyerCreated->id;
                //JAppActivationHelper::log('New buyer dataset added. Buyer ID: ' . $buyerID, JLog::INFO);
            }

            // Create new license
            $licenseData = array();
            $licenseData['buyer_id'] = $buyerID;
            $licenseData['product_id'] = $productData[0]['id'];
            $licenseData['max_majver'] = $productData[0]['default_majver'];
            $licenseData['date_purchase'] = Carbon::now()->format('Y-m-d');
            $licenseData['seats'] = '1';      // Always 1 seat per pre-code
            $licenseData['notes'] =  'This license was created by using the pre-activation code: '.substr(chunk_split($precode, 5, '-'), 0, -1);

            // Set license type
            $licenseData['type'] = env('LICENSE_TYPE_BASE');
            $licenseData['support_days'] = $licenseData['license_days'] = 365;

            if($activationMode == env('JAA_PRE_ACTIVATION_TEMP')) {
                $licenseData['type'] = env('TEMP_BASE');

                $codeData = json_decode($precodeData[0]['data']);
                $licenseData['license_days'] = $codeData->temp_days;
            }

            dd($licenseData);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
