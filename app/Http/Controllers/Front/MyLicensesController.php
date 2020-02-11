<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Buyers\Buyers;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\MyLicenses\MyLicenses;
use App\Http\Models\Front\Precode\Precode;
use App\Http\Models\Front\Products\Products;
use App\Http\Models\Helper\Helper;
use App\Http\Models\IlokCodes\IlokCodes;
use App\Http\Models\License\Seats;
use App\Http\Models\Paddle\Paddle;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\License\License;
use App\Http\Models\Front\MyLicenses\NotesToLicense;
class MyLicensesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/my-licenses';
        $breadcrumbs[0]['text'] = trans('main.my_licenses');

        $categories = ProductsPageCategory::getCategoriesTolist();
        $licenses = MyLicenses::getLicensesByUser();

        return view('Front.my_licenses')->with([
            'categories' => $categories,
            'licenses'=>$licenses,
            'breadcrumbs' => $breadcrumbs,
            'meta_title' => trans('main.my_licenses_title')
        ]);
    }

    public function queueCancelSubscription(Request $request)
    {
        $result = Paddle::queueCancelSubscription($request->licenseid);
       return redirect()->back();
    }

    public function getProductPublishedState(Request $request){

        $productData = Products::lookupByPaddlePID($request->paddle_pid);

        if(empty($productData)) {
            self::_finishPublishState(false, 'Data error, please try again or contact us for assistance.');
        }

        if(!$productData[0]['published']) {
            self::_finishPublishState(false, 'This purchase is temporary not available, please try again later.');
        }

        self::_finishPublishState(true);
    }

    /**
     * Finishes the product published state method
     */
    public function _finishPublishState($result, $message = '')
    {

        $resultArray = array('isPublished' => $result, 'message' => $message);
        echo json_encode($resultArray);
    }

    public function fulfillment(Request $request)
    {
        $result_array = array();
        $precode = str_replace('-','',$request->code);
        $formattedPrecode = substr(chunk_split($precode, 5, '-'), 0, -1);

        // Check security token on manual activation requests
        $UserID = $request->user_id;
        if ($UserID != Auth::ID())
        {
            $result_array['status'] = false;
            $result_array['text'] = 'attemp';
            return $result_array;
        }
        $productData = MyLicenses::lookupByPreCode($precode);
        if ($productData == false){
            $result_array['status'] = false;
            $result_array['text'] = 'Code already consumed';
            return $result_array;
        }

        // Get pre-activation type
        $precodeData = Precode::getData($precode);
        $activationMode = intval($precodeData[0]['type']);

        // If feature activation, check if prefix is set in product settings
        if($activationMode == intval(env('JAA_PRE_ACTIVATION_FEATURE'))) {

            $codePrefix = substr($precode, 0, 5);
            if(strpos($productData[0]['feature_prefixes'], $codePrefix) === false) {
            }
        }

        // Serial or iLok code delivered for upgrade or feature activation?
        if ($activationMode == intval(env('JAA_PRE_ACTIVATION_UPGRADE')) || $activationMode ==  intval(env('JAA_PRE_ACTIVATION_FEATURE'))) {

            $upgradeSerial = $request->serial;
            $upgradeiLokCode = $request->ilok;

            if(empty($upgradeSerial) && empty($upgradeiLokCode)) {

                $result_array['status'] = false;
                $result_array['text'] = 'No serial provided';
                return $result_array;
            }
        }

        // Full activation mode handler


        $postData = $request->all();
        if($activationMode == env('JAA_PRE_ACTIVATION_FULL') || $activationMode == env('JAA_PRE_ACTIVATION_TEMP')) {

            $buyerFields = array_flip(array('last', 'first', 'company', 'street1', 'street2', 'zip', 'city', 'country', 'state', 'email', 'phone', 'website'));
            $buyerData = array_intersect_key($postData, $buyerFields);

            // Verify important buyer data
            if(!isset($buyerData['email']) || empty($buyerData['email'])) {

                $result_array['status'] = false;
                $result_array['text'] = 'Buyer email missing';
                return $result_array;

            }

            // Check if buyer already exists, if not create new
            $buyer = Buyers::buyerLookupByMail($buyerData['email']);

            if(!empty($buyer))
            {
                $buyerID = $buyer[0]['id'];     // Get existing buyer's ID
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

                    $result_array['status'] = false;
                    $result_array['text'] = 'Buyer lastname missing';
                    return $result_array;

                }
                if(!isset($buyerData['first']) || empty($buyerData['first'])) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Buyer firstname missing';
                    return $result_array;

                }
                // Create new buyer
                $buyerCreated = Buyers::create($buyerData);

                if(!$buyerCreated)
                {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Component error 1';
                    return $result_array;
                }

                $buyerID = $buyerCreated->id;
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

            // Generate serial or get iLok code
            if ($activationMode == env('JAA_PRE_ACTIVATION_TEMP') || intval($productData['0']['licsystem']) == intval(env('LL_LicenseLib')))
            {
                $licenseData['serial'] = str_replace('-','',License::generateSerialNumber());
            }
            else
            {
                $licenseData['ilok_code'] = IlokCodes::getFreeCode($productData[0]['id']);

                if (empty($licenseData['ilok_code']))
                {
                   //Hepler::sendIlokStockWarningMail();
                    $result_array['status'] = false;
                    $result_array['text'] = 'Component error 2';
                    return $result_array;
                }
            }

            // Store new license
            $result =  License::create($licenseData);

            if(empty($result))
            {
                $result_array['status'] = false;
                $result_array['text'] = 'Component error 3';
                return $result_array;
            }
            else
            {

                $currentLicenseID = $result->id;
                Helper::sendSerialMail($currentLicenseID);
                if (isset($licenseData['ilok_code']))
                {
                    //JAppActivationHelper::log('-> iLok Code ' . $licenseData['ilok_code'], JLog::INFO);
                }
                else
                {
                    //JAppActivationHelper::log('-> Serial ' . $licenseData['serial'], JLog::INFO);
                }

                if($activationMode == env('JAA_PRE_ACTIVATION_TEMP')) {
                    //JAppActivationHelper::log('-> Temp license duration: ' . $licenseData['license_days'] . ' days', JLog::INFO);
                }

            }

            // Invalidate iLok code (mark as used)
            if (isset($licenseData['ilok_code'])) {
                if(intval($productData[0]['debug_mode']) == 0) {
                    IlokCodes::consumeCode($licenseData['ilok_code']);
                   // JAppActivationHelper::log('Mmmhhhh: Used iLok code tastes perfect!', JLog::INFO);
                } else {
                   // JAppActivationHelper::log('Debug mode enabled, will not eat iLok code!', JLog::INFO);
                }
            }
           // dd($licenseData);
        }else {
            // Upgrade mode handler (feature & product)
            // Try to get license data by serial or iLok code, also try old activation component
            if ($upgradeiLokCode) {
                $licenseData = (array)License::lookupByilokCode($upgradeiLokCode);
            } else {
                $licenseData = (array)License::lookupBySerial($upgradeSerial);
            }
            $isLegacyLicense = false;
            if(empty($licenseData)) {

                $licenseData = (array)License::lookupOldSerial($upgradeSerial);
                if(!empty($licenseData)) {
                    $isLegacyLicense = true;
                    $licenseData['product_id'] .= 'L';  // Append legacy product identifier
                }
            }

            // If no license data, exit
            if(empty($licenseData)) {
                $result_array['status'] = false;
                $result_array['text'] = 'No upgradeable license found';
                return $result_array;
            }

            // Check license and product details
            if(!$isLegacyLicense) {
                $checkResult = true;
                if(intval($licenseData['product_isbeta'])) {
                    $checkResult = false;
                }
                if(intval($licenseData['type']) !== env('LICENSE_TYPE_BASE')) {
                    $checkResult = false;
                }
                if(intval($licenseData['seats']) > 1) {
                    $checkResult = false;
                }
                if(!$checkResult) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Upgrade not possible';
                    return $result_array;
                }

                // Check if license is currently used
                $activeSeats = Seats::getLicenseSeats($licenseData['id']);

                if(count($activeSeats)) {

                    $result_array['status'] = false;
                    $result_array['text'] = 'This license is active, please deactivate before upgrade';
                    return $result_array;

                }
            } else {
                // Check if license is active or invalid
                if(!empty($licenseData['activation_code'])) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'This license is active, please deactivate before upgrade';
                    return $result_array;
                }
                if(boolval($licenseData['invalid'])) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'This license is marked as invalid';
                    return $result_array;
                }
            }

            // Perform some last checks before upgrade, distinguish between product and feature upgrade
            if($activationMode === env('JAA_PRE_ACTIVATION_UPGRADE')) {
                // Is old product in list of upgradeable products?
                $upgradeableProductIDs = json_decode($productData[0]['upgradeable_products']);
                if(!in_array($licenseData['product_id'], $upgradeableProductIDs)) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Code not valid for this upgrade';
                    return $result_array;
                }

                // Try to get buyer data of legacy license
                if($isLegacyLicense) {
                    // Try to get legacy buyer before creating new
                    $legacyBuyer = Buyers::getOldBuyer($licenseData['buyer_id']);
                    if(empty($legacyBuyer)) {
                        $result_array['status'] = false;
                        $result_array['text'] = 'Legacy buyer data not found';
                        return $result_array;
                    }

                    // Try to get buyer in new activation component, else create new
                    $buyer = Buyers::buyerLookupByMail($legacyBuyer['email']);
                    if(empty($buyer)) {
                        // Create new buyer
                        $newBuyerData = array(
                            'first' => $legacyBuyer[0]['first'],
                            'last' => $legacyBuyer[0]['last'],
                            'email' => $legacyBuyer[0]['email'],
                            'user_id' => Auth::ID()
                        );
                        $buyerID = Buyers::create($newBuyerData);
                        if ($buyerID !== false) {
                            $buyer = $buyerID;
                        }
                        if(empty($buyer)) {
                            $result_array['status'] = false;
                            $result_array['text'] = 'Error creating buyer';
                            return $result_array;
                        }
                    }
                }

                // Prepare new license
                $newLicenseData = array();
                if($isLegacyLicense) {
                    $newLicenseData['buyer_id'] = $buyer->id;
                    $newLicenseData['serial'] = $licenseData['serial'];
                    //$newLicenseData['notes'] = JText::sprintf('COM_JAPPACTIVATION_REMOTE_PURCHASE_UPGRADE', $formattedPrecode, JDate::getInstance('now', 'UTC')->format('Y-m-d'));
                    $newLicenseData['notes'] = sprintf("This license was upgraded by using Pre-Activation Code '%s' on %s", $formattedPrecode,  Carbon::now()->format('Y-m-d'));
                } else {
                    $newLicenseData = $licenseData;
                    //$newLicenseData['notes'] .= JText::sprintf('COM_JAPPACTIVATION_REMOTE_PURCHASE_UPGRADE', $formattedPrecode, JDate::getInstance('now', 'UTC')->format('Y-m-d'));
                    $newLicenseData['notes'] .= sprintf("This license was upgraded by using Pre-Activation Code '%s' on %s", $formattedPrecode,  Carbon::now()->format('Y-m-d'));
                }

                $newLicenseData['product_id'] = $productData[0]['id'];
                $newLicenseData['max_majver'] = $productData[0]['default_majver'];
                $newLicenseData['date_purchase'] =  Carbon::now()->format('Y-m-d');
                $newLicenseData['type'] = env('LICENSE_TYPE_BASE');
                $newLicenseData['seats'] = '1';      // Always 1 seat per pre-code
                $newLicenseData['support_days'] = $licenseData['license_days'] = 365;

                // Add or reuse iLok code
                if (intval($productData[0]['licsystem']) === env('LICENSE_SYSTEM_PACE'))
                {
                    if (empty($newLicenseData['ilok_code']))
                    {
                        $newLicenseData['ilok_code'] = IlokCodes::getFreeCode($productData[0]['id']);
                        if (empty($newLicenseData['ilok_code']))
                        {
                            $result_array['status'] = false;
                            $result_array['text'] = 'Component error 4';
                            return $result_array;
                        }
                    }
                }
                // Update/Create license
                if(License::create($newLicenseData)) {
                    //JAppActivationHelper::log('License successfully created/updated', JLog::INFO);
                } else {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Component error 5';
                    return $result_array;
                }

                // Determine iLok -> iLok upgrade and just send an instruction email to the customer in this case
                if ($upgradeiLokCode)
                {
                    Helper::sendIlokUpgradeMail($newLicenseData['id']);
                   // JAppActivationHelper::log('iLok upgrade mail sent to customer', JLog::INFO);
                }
                else
                {
                    // Send serial mail to customer
                    if(!$isLegacyLicense) {
                        Helper::sendSerialMail($newLicenseData['id']);
                        //JAppActivationHelper::log('Serial mail sent to customer', JLog::INFO);
                    }
                }

                // Delete old (legacy) license
                //$licenseModelFrontend = JModelLegacy::getInstance('LicenseFrontend', 'JAppActivationModel');
                if($isLegacyLicense) {
                    if(!License::deleteOldLicense($licenseData['id'])) {
                        $result_array['status'] = false;
                        $result_array['text'] = 'Deletion of legacy license failed';
                        return $result_array;
                    }
                    //JAppActivationHelper::log('Legacy license deleted');
                }

                // Invalidate iLok code for purchases (mark as used)
                if (!$upgradeiLokCode && isset($newLicenseData['ilok_code'])) {
                    if(intval($productData[0]['debug_mode']) == 0) {
                        IlokCodes::consumeCode($newLicenseData['ilok_code']);
                        //JAppActivationHelper::log('Mmmhhhh: Used iLok code tastes perfect!', JLog::INFO);
                    } else {
                        //JAppActivationHelper::log('Debug mode enabled, will not eat iLok code!', JLog::INFO);
                    }
                }
            }
            else
            {
                // Compare product IDs
                if($productData[0]['id'] !== $licenseData['product_id']) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Products do not match';
                    return $result_array;
                }

                // Feature comparison
                $featureNames = explode(',', $productData[0]['features']);
                $featurePrefixes = explode(',', $productData[0]['feature_prefixes']);
                $purchasedFeatureBit = array_search($codePrefix, $featurePrefixes);
                $featureValues = Helper::bitmask2feature($licenseData['prod_features']);

                if($featureValues[$purchasedFeatureBit] == 1) {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Feature flag is already set';
                    return $result_array;

                }

                // Set new feature bit
                $featureValues[$purchasedFeatureBit] = 1;
                // Convert to feature set
                $newFeatureset = array_keys(array_filter($featureValues));
                $licenseData['prod_features'] = $newFeatureset;
                $licenseData['notes'] .= sprintf("Feature '%s' was created on %s using Pre-Activation Code: %s", $featureNames[$purchasedFeatureBit], Carbon::now()->format('Y-m-d'), $formattedPrecode);
                $lic = License::create($licenseData);
                if($lic) {
                    //JAppActivationHelper::log('Purchased feature set: ' . $featureNames[$purchasedFeatureBit], JLog::INFO);
                    Helper::sendSerialMail($lic['id']);
                } else {
                    $result_array['status'] = false;
                    $result_array['text'] = 'Component error 6';
                    return $result_array;
                }
                // Send serial mail to customer

                //JAppActivationHelper::log('Serial mail sent to customer', JLog::INFO);
            }
        }
        // Invalidate pre-activation code (mark as used)
        if(intval($productData[0]['debug_mode']) == 0) {
            Precode::consumeCode($precode);
            //JAppActivationHelper::log('Yummy: Pre-Activation Code eliminated!', JLog::INFO);
        } else {
            //JAppActivationHelper::log('Debug mode enabled, will not consume Pre-Activation Code!', JLog::INFO);
        }

        // Sending serial to client
        if (isset($licenseData['serial'])) {
           // echo(JAppActivationHelper::cleanFormattedString($licenseData['serial'], '-'));
        }

       // $this->_finishFulfillment();
        $result_array['status'] = true;
        $result_array['text'] = 'Your code has been activated';
        return $result_array;
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

    public function updateUserNotes(Request $request){
        NotesToLicense::updateNotes($request);
        return redirect()->back();
    }
}
