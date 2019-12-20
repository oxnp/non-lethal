<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Buyers\Buyers;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\MyLicenses\MyLicenses;
use App\Http\Models\Front\Precode\Precode;
use App\Http\Models\Helper\Helper;
use App\Http\Models\IlokCodes\IlokCodes;
use App\Http\Models\License\Seats;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\License\License;

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
        $formattedPrecode = substr(chunk_split($precode, 5, '-'), 0, -1);

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
            $upgradeiLokCode = $request->ilok;

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

            // Generate serial or get iLok code
            if ($activationMode == env('JAA_PRE_ACTIVATION_TEMP') || intval($productData['0']['licsystem']) === env('LL_LicenseLib'))
            {
                $licenseData['serial'] = str_replace('-','',License::generateSerialNumber());
            }
            else
            {
                $licenseData['ilok_code'] = IlokCodes::getFreeCode($productData[0]['id']);
                if (empty($licenseData['ilok_code']))
                {
                    //JAppActivationHelper::log('No more ilok codes on stock!!!', JLog::ERROR);
                    //$this->sendIlokStockWarningMail(); send mail
                    //$this->_finishFulfillment();
                    return 'Component error';
                }
            }
            // Store new license
            $result =  License::create($licenseData);
            if(empty($result))
            {
                //JAppActivationHelper::log('Error creating new license', JLog::ERROR);
                //$this->_finishFulfillment();
                return 'Component error';
            }
            else
            {
                $currentLicenseID = $result->id;
                //JAppActivationHelper::log('New license created:', JLog::INFO);
                //JAppActivationHelper::log('-> ID ' . $currentLicenseID, JLog::INFO);

                if ($licenseData['ilok_code'])
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

                // Reset model to enable multiple stores
                //$licenseModel->setState('license.id', null);
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
            dd($licenseData);
        }
        else {
            // Upgrade mode handler (feature & product)

            // Try to get license data by serial or iLok code, also try old activation component
            if ($upgradeiLokCode) {
                //JAppActivationHelper::log('Upgrade iLok code ' . $upgradeiLokCode . ' received, trying to get license data now...', JLog::INFO);
                $licenseData = (array)License::lookupByilokCode($upgradeiLokCode);
            } else {
                //JAppActivationHelper::log('Upgrade serial ' . $upgradeSerial . ' received, trying to get license data now...', JLog::INFO);
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
                //JAppActivationHelper::log('No upgradeable license found for the given serial/iLok!', JLog::NOTICE);
                //$this->_finishFulfillment();
                //throw new Exception('No upgradeable license found', 403);
                return 'No upgradeable license found';
            }
            //JAppActivationHelper::log('Found license with ID = ' . $licenseData['id'] . ' now checking upgrade permission...', JLog::INFO);

            // Check license and product details
            if(!$isLegacyLicense) {
                $checkResult = true;
                if(intval($licenseData['product_isbeta'])) {
                    $checkResult = false;
                    //JAppActivationHelper::log('Beta versions are not eligible to upgrade, aborting...', JLog::NOTICE);
                }
                if(intval($licenseData['type']) !== env('LICENSE_TYPE_BASE')) {
                    $checkResult = false;
                    //JAppActivationHelper::log('Subscriptions, temporary or invalid licenses are not eligible to upgrade, aborting...', JLog::NOTICE);
                }
                if(intval($licenseData['seats']) > 1) {
                    $checkResult = false;
                    //JAppActivationHelper::log('Multiseat licenses are not eligible to upgrade, aborting...', JLog::NOTICE);
                }
                if(!$checkResult) {
                    //$this->_finishFulfillment();
                    //throw new Exception('Upgrade not possible', 403);
                    return 'Upgrade not possible';
                }

                // Check if license is currently used
              //  $licensesModel = JModelLegacy::getInstance('Licenses', 'JAppActivationModel');
                $activeSeats = Seats::getLicenseSeats($licenseData['id']);

                if(count($activeSeats)) {
                    //JAppActivationHelper::log('License is in use, aborting...', JLog::NOTICE);
                   // $this->_finishFulfillment();
                   // throw new Exception('This license is active, please deactivate before upgrade', 423);
                    return 'This license is active, please deactivate before upgrade';
                }
            } else {

                // Check if license is active or invalid
                if(!empty($licenseData['activation_code'])) {
                   // JAppActivationHelper::log('Old license currently in use, removal failed!', JAppActivationLog::NOTICE);
                   // $this->_finishFulfillment();
                   // throw new Exception('This license is active, please deactivate before upgrade', 423);
                    return 'This license is active, please deactivate before upgrade';
                }
                if(boolval($licenseData['invalid'])) {
                   // JAppActivationHelper::log('Old license is invalid, upgrade denied!', JAppActivationLog::NOTICE);
                   // $this->_finishFulfillment();
                    //throw new Exception('This license is marked as invalid', 403);
                    return 'This license is marked as invalid';
                }
            }

            // Perform some last checks before upgrade, distinguish between product and feature upgrade
            if($activationMode === env('JAA_PRE_ACTIVATION_UPGRADE')) {

                // Is old product in list of upgradeable products?
                $upgradeableProductIDs = json_decode($productData[0]['upgradeable_products']);
                if(!in_array($licenseData['product_id'], $upgradeableProductIDs)) {
                    //JAppActivationHelper::log('Product not in list of upgradeable products!', JLog::NOTICE);
                    //JAppActivationHelper::log('User tried to upgrade from ' . $licenseData['product_name'], JLog::NOTICE);
                    //$this->_finishFulfillment();
                    //throw new Exception('Code not valid for this upgrade', 403);
                    return 'Code not valid for this upgrade';
                }

                // Try to get buyer data of legacy license
                if($isLegacyLicense) {

                    // Try to get legacy buyer before creating new
                    $legacyBuyer = Buyers::getOldBuyer($licenseData['buyer_id']);
                    if(empty($legacyBuyer)) {
                        //JAppActivationHelper::log('Cannot find legacy buyer', JAppActivationLog::ERROR);
                       // $this->_finishFulfillment();
                       // throw new Exception('Legacy buyer data not found', 500);
                        return  'Legacy buyer data not found';
                    }

                    // Try to get buyer in new activation component, else create new
                    $buyer = Buyers::buyerLookupByMail($legacyBuyer->email);
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
                           // JAppActivationHelper::log('Cannot create buyer with data:', JAppActivationLog::ERROR);
                           // JAppActivationHelper::log(json_encode($newBuyerData));
                           // $this->_finishFulfillment();
                           // throw new Exception('Error creating buyer', 500);
                            return 'Error creating buyer';
                        }
                        //JAppActivationHelper::log('Buyer created with buyer ID: ' . $buyer->id);
                       // JAppActivationHelper::log('Joomla user ID: ' . $buyer->joomla_userid);
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
                            //JAppActivationHelper::log('No more iLok codes in stock!!!', JLog::ERROR);
                            //$this->_finishFulfillment();
                            //throw new Exception('Component error', 500);
                            return 'Component error';
                        }
                    }
                }

                // Update/Create license
                if(License::create($newLicenseData)) {
                    //JAppActivationHelper::log('License successfully created/updated', JLog::INFO);
                } else {
                   // JAppActivationHelper::log('Error creating/updating license!', JLog::ERROR);
                   // $this->_finishFulfillment();
                   // throw new Exception('Component error', 500);
                    return 'Component error';
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
                $licenseModelFrontend = JModelLegacy::getInstance('LicenseFrontend', 'JAppActivationModel');
                if($isLegacyLicense) {
                    if(!$licenseModelFrontend->deleteOldLicense($licenseData['id'])) {
                        JAppActivationHelper::log('Deletion of old license from old activation component failed!', JAppActivationLog::NOTICE);
                        $this->_finishFulfillment();
                        throw new Exception('Deletion of legacy license failed', 500);
                    }
                    JAppActivationHelper::log('Legacy license deleted');
                }

                // Invalidate iLok code for purchases (mark as used)
                if (!$upgradeiLokCode && isset($newLicenseData['ilok_code'])) {
                    if(intval($productData->debug_mode) == 0) {
                        $ilokModel->consumeCode($newLicenseData['ilok_code']);
                        JAppActivationHelper::log('Mmmhhhh: Used iLok code tastes perfect!', JLog::INFO);
                    } else {
                        JAppActivationHelper::log('Debug mode enabled, will not eat iLok code!', JLog::INFO);
                    }
                }
            }


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
