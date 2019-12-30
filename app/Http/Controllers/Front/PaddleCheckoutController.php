<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Buyers\Buyers;
use App\Http\Models\Front\MyLicenses\MyLicenses;
use App\Http\Models\Helper\Helper;
use App\Http\Models\IlokCodes\IlokCodes;
use App\Http\Models\License\License;
use App\Http\Models\License\Seats;
use App\Http\Models\Paddle\Paddle;
use App\Http\Models\Products\Products;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaddleCheckoutController extends Controller
{
    public  function paddle_gateway(Request $request){

        $alert_name = 'ps_'.$request->alert_name;

        self::$alert_name($request);
    }

    public function ps_subscription_updated($request){
        // Get license information
        $license = License::lookupByPaddleSID($request->subscription_id);

        $licenseData = License::find($license[0]['id']);

        if($license->isEmpty()) {
            //JAppActivationHelper::log('No License found to update!');
           // JAppActivationHelper::log('-> Paddle Subscription ID: ' . $postData->subscription_id);
            return false;
        }

        // Set new status and add note
        $licenseData->paddle_status = $request->status;
        $licenseData->paddle_next_billdate = $request->next_bill_date;
        $licenseData->notes = $licenseData->notes.'<p>'.sprintf('%s - This subscription was updated by Paddle checkout. Status was set to %s', Carbon::now()->format('Y-m-d h:i a'),$request->status).'</p>';

        // Try to save license
        $result = $licenseData->save();
        if(! $result) {
            //JAppActivationHelper::log('Subscription update failed!');
            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $postData->subscription_id);
            //JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
            return false;
        }
        //JAppActivationHelper::log('Subscription update successful!');
        //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $postData->subscription_id);
        //JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
        return true;

    }

    public  function ps_subscription_payment_succeeded(Request $request){

        // Get license information
        $license = License::lookupByPaddleSID($request->subscription_id);
        $licenseData = License::find($license[0]['id']);
        if($license->isEmpty()) {
            //JAppActivationHelper::log('No License found to update!');
            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $postData->subscription_id);
            return false;
        }

        // If license will expire within the next month, send a mail to the customer
        $daysLeft = Helper::getDaysTillSubscriptionRenewal($licenseData->date_purchase);
        if($daysLeft <= 40) {   // Use 40 days as trigger, just to be sure to catch any alerts
            //$this->sendNotificationMail($licenseData);
        }

        // Set new data and add note
        $licenseData->paddle_status = $request->status;
        $licenseData->paddle_next_billdate = $request->next_bill_date;
        $licenseData->notes = $licenseData->notes.'<p>'.sprintf('%s - Monthly payment received via %s. Status was set to %s.',
            Carbon::now()->format('Y-m-d h:i a'),$request->payment_method,$request->status).'</p>';

        // Try to save license
        $result = $licenseData->save();
        if(! $result) {
           // JAppActivationHelper::log('Subscription update failed!');
            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $request->subscription_id);
           // JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
            return false;
        }

        //JAppActivationHelper::log('Subscription updated successful!');
        //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $request->subscription_id);
        //JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
        return true;
    }

    public  function ps_subscription_payment_failed(Request $request){
        // Get license information
        $license = License::lookupByPaddleSID($request->subscription_id);
        $licenseData = License::find($license[0]['id']);
        if($license->isEmpty()) {

           // JAppActivationHelper::log('No License found to update!');
            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $request->subscription_id);
            return false;
        }

        // If license will expire within the next month, send a mail to the customer
        if($licenseData->paddle_status == 'active') {
            $daysLeft = Helper::getDaysTillSubscriptionRenewal($licenseData->date_purchase);
            if($daysLeft <= 40) {   // Use 40 days as trigger, just to be sure to catch any alerts
                //$this->sendNotificationMail($licenseData, 'renewal');
            }
        }

        // Set new data and add note
        $licenseData->paddle_status = $request->status;
        $licenseData->notes = $licenseData->notes.'<p>'.sprintf('%s - Monthly payment failed! Status was set to %s.',
            Carbon::now()->format('Y-m-d h:i a'), $request->status).'</p>';

        // Try to save license
        $result = $licenseData->save();
        if(! $result) {
            //JAppActivationHelper::log('Subscription update failed!');
            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $request->subscription_id);
            //JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
            return false;
        }

        // If new license status is set to 'past_due', send a mail to customer
        if($licenseData->paddle_status == 'past_due') {
            //$this->sendNotificationMail($licenseData, 'payment_failed');
        }

        //JAppActivationHelper::log('Subscription update successful!');
        //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $request->subscription_id);
        //JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
        return true;
    }

    public  function ps_subscription_created(){

    }

    public  function ps_subscription_cancelled(Request $request){
        // Get license information
        $license = License::lookupByPaddleSID($request->subscription_id);

        $licenseData = License::find($license[0]['id']);

        if($license->isEmpty()) {
            //JAppActivationHelper::log('No License found to update!');
            // JAppActivationHelper::log('-> Paddle Subscription ID: ' . $postData->subscription_id);
            return false;
        }

        // Set new status and add note
        $licenseData->paddle_status = $request->status;
        $licenseData->notes .=$licenseData->notes.'<p>'.sprintf('%s - This subscription was cancelled by Paddle checkout. Status was set to %s',$request->cancellation_effective_date,$request->status).'</p>';

        // Try to save license
        // Try to save license
        $result = $licenseData->save();
        if(! $result) {
            //JAppActivationHelper::log('Subscription update failed!');
            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $postData->subscription_id);
            //JAppActivationHelper::log('-> License ID: ' . $licenseData->id);
            return false;
        }

        //JAppActivationHelper::log('Subscription cancelled successful!');
        //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $request->subscription_id);
        //JAppActivationHelper::log('-> License ID: ' . $request->id);
        return true;
    }

    public  function ps_payment_succeeded(Request $request){
        // Get required backend models
/*      JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models/');
        $buyerModel = JModelLegacy::getInstance('Buyer', 'JAppActivationModel');
        $licenseModel = JModelLegacy::getInstance('License', 'JAppActivationModel');
        $licenseModelFrontend = JModelLegacy::getInstance('LicenseFrontend', 'JAppActivationModel');
        $productModel = JModelLegacy::getInstance('Product', 'JAppActivationModel');
        $seatsModel = JModelLegacy::getInstance('Seats', 'JAppActivationModel');
        $ilokModel = JModelLegacy::getInstance('IlokCode', 'JAppActivationModel');
*/
        // Get complete POST data
        //$postData = (object)JFactory::getApplication()->input->getArray();
        $passthroughData = json_decode(base64_decode($request->passthrough));
        if(empty($passthroughData)) {
            ///JAppActivationHelper::log('No passthrough data received!');
            return false;
        }



        // Check if order was sucessfully processed earlier
        $newProductIsSubscription = isset($request->subscription_id);
        if($newProductIsSubscription) {
            $paddleSID = $request->subscription_id;
            $license = License::lookupByPaddleSID($paddleSID);
            $logText = '-> Paddle Subscription ID: ' . $paddleSID;
        } else {
            $paddleOID = $request->order_id;
            $license = License::lookupByPaddleOID($paddleOID);
            $logText = '-> Paddle Order ID: ' . $paddleOID;

        }

        if(!$license->isEmpty()) {
            //JAppActivationHelper::log('This purchase was already processed, skipping request!');
            //JAppActivationHelper::log($logText);
            //JAppActivationHelper::log('-> License ID: ' . $license->id);

            return false;
        }

        // Get product information
        $paddleProductID = $newProductIsSubscription ? $request->subscription_plan_id : $request->product_id;
        $product = Products::lookupByPaddlePID($paddleProductID);

        if($product->isEmpty()) {
            //JAppActivationHelper::log('No Paddle product relationship found!');
            //JAppActivationHelper::log('Set Paddle PID ' . $paddleProductID . ' for product ' . $postData->product_name);
            return false;
        }


        // Abort if product is not published
        if(!$product[0]['published']) {
            //JAppActivationHelper::log('Product ' . $product[0]['name'] . ' not published, aborting...', JAppActivationLog::NOTICE);
            return false;
        }

        // If upgrade serial provided lookup license data

        $buyer = null;
        $isiLokUpgrade = !empty($passthroughData->upgradeiLok);
        $isLegacyUpgrade = isset($passthroughData->upgradeSerial);


        if($isLegacyUpgrade || $isiLokUpgrade)
        {
            //JAppActivationHelper::log('User wants to upgrade to ' . $product[0]['name'] . ' (Product ID: ' . $product[0]['id']. ')');

            // Check if product is in beta state
            if($product[0]['isbeta']) {
                //JAppActivationHelper::log('Upgrade to products in beta prohibited!');
                return false;
            }

            $oldLicenseData = null;
            if ($isiLokUpgrade)
            {
                //JAppActivationHelper::log('Upgrade iLok code delivered: ' . $passthroughData->upgradeiLok);
                $oldLicenseData = License::lookupByilokCode($passthroughData->upgradeiLok);
            }
            else
            {
                //JAppActivationHelper::log('Upgrade serial delivered: ' . $passthroughData->upgradeSerial);
                $oldLicenseData = License::lookupBySerial($passthroughData->upgradeSerial);
            }

            if(empty($oldLicenseData)) {

                // Try to get license data from old activation component (if installed)
                /*if(JComponentHelper::isInstalled('com_appactivation')) {
                    $oldLicenseData = License::lookupOldSerial($passthroughData->upgradeSerial);
                    if(!empty($oldLicenseData)) {
                        //JAppActivationHelper::log('License found in old activation component');

                        // Check license upgrade eligibility
                        $upgradeableProducts = (array)json_decode($product[0]['upgradeable_products']);
                        var_dump($upgradeableProducts); die();
                        $legacyProductID = $oldLicenseData->product_id . 'L';
                        if(!in_array($legacyProductID, $upgradeableProducts)) {
                           // JAppActivationHelper::log('Old product not in list of upgradeable products', JAppActivationLog::NOTICE);
                            return false;
                        }

                        // Check if license (is active) or invalid
                        if(!empty($oldLicenseData->activation_code)) {
                            JAppActivationHelper::log('Old license currently in use, removal failed!', JAppActivationLog::NOTICE);
                            return false;
                        }
                        if(boolval($oldLicenseData->invalid)) {
                            //JAppActivationHelper::log('Old license is invalid, upgrade denied!', JAppActivationLog::NOTICE);
                            return false;
                        }

                        // Try to get buyer before creating new
                        $buyer = $buyerModel->buyerLookupByMail($passthroughData->email);
                        if(empty($buyer)) {

                            // Get old buyer data from old activation component
                            $oldBuyerData = $buyerModel->getOldBuyer($oldLicenseData->buyer_id);
                            if(empty($oldBuyerData)) {
                                JAppActivationHelper::log('Loading old buyer data failed!', JAppActivationLog::NOTICE);
                                return false;
                            }

                            // Create new buyer
                            $newBuyerData = array(
                                'first' => $oldBuyerData->first,
                                'last' => $oldBuyerData->last,
                                'email' => $passthroughData->email      // Set current email for this purchase
                            );
                            $buyerID = $buyerModel->save($newBuyerData, true);
                            if ($buyerID !== false) {
                                $buyer = $buyerModel->getItem($buyerID);
                            }
                            if(empty($buyer)) {
                                JAppActivationHelper::log('Cannot create buyer with data:', JAppActivationLog::ERROR);
                                JAppActivationHelper::log(json_encode($newBuyerData));
                                return false;
                            }
                            JAppActivationHelper::log('Buyer created with buyer ID: ' . $buyer->id);
                            JAppActivationHelper::log('Joomla user ID: ' . $buyer->joomla_userid);
                        }

                        // Delete old license from old activation component
                        if(!$licenseModelFrontend->deleteOldLicense($oldLicenseData->id)) {
                            JAppActivationHelper::log('Deletion of old license from old activation component failed!', JAppActivationLog::NOTICE);
                            return false;
                        }
                        JAppActivationHelper::log('Old license deleted from old activation component');

                    } else {
                        JAppActivationHelper::log('Cannot find existing license in old activation component for this serial!', JAppActivationLog::NOTICE);
                        return false;
                    }
                } else {
                    JAppActivationHelper::log('Cannot find existing license/subscription for this serial!', JAppActivationLog::NOTICE);
                    return false;
                } */
            } else {
                //JAppActivationHelper::log('Old license/subscription found!');
               // JAppActivationHelper::log('-> License ID: ' . $oldLicenseData->id);

                // Check if old license is active
               // $seatsModel->setState('id', $oldLicenseData->id);
                //$seats = $seatsModel->getItems();

                $seats = Seats::whereId($oldLicenseData[0]['id'])->get();
                if(!$seats->isEmpty()) {
                    //JAppActivationHelper::log('Old license/subscription currently in use, removal failed!', JAppActivationLog::NOTICE);
                    return false;
                }

                // Check license upgrade eligibility
                $upgradeableProducts = (array)json_decode($product[0]['upgradeable_products']);
                if(!in_array($oldLicenseData[0]['product_id'], $upgradeableProducts)) {
                    //JAppActivationHelper::log('Product not in list of upgradeable products', JAppActivationLog::NOTICE);
                    return false;
                }
                if($oldLicenseData[0]['type'] == env('SUBSCRIPTION_BASE')) {
                    if($oldLicenseData[0]['paddle_status'] == env('PADDLE_STATUS_DELETED')) {
                        //JAppActivationHelper::log('This subscription has been cancelled, upgrade not possible!', JAppActivationLog::NOTICE);
                        return false;
                    }
                } elseif ($oldLicenseData[0]['type'] != env('LICENSE_TYPE_BASE')) {
                    //JAppActivationHelper::log('Upgrades are only available for permanent licenses and active subscriptions!', JAppActivationLog::NOTICE);
                    return false;
                }
                if(intval($oldLicenseData[0]['seats']) > 1) {
                    //JAppActivationHelper::log('User tried to upgrade multi-seat license -> Not supported (yet)', JAppActivationLog::NOTICE);
                    return false;
                }

                // Does the serial belong to the logged in user?
               // $buyer = $buyerModel->getItem($oldLicenseData->buyer_id);
                $buyer = Buyers::getBuyerById($oldLicenseData[0]['buyer_id']);

                if($buyer->email != $passthroughData->email) {
                    //JAppActivationHelper::log('Buyer emails mismatch, possible wrong user logged in on purchase', JAppActivationLog::NOTICE);
                    //JAppActivationHelper::log('Mail address of logged in user: ' . $passthroughData->email, JAppActivationLog::NOTICE);
                    //JAppActivationHelper::log('Mail address of license owner: ' . $buyer->email, JAppActivationLog::NOTICE);
                    return false;
                }

                // Cancel subscription via Paddle API
                if($oldLicenseData[0]['type'] == env('SUBSCRIPTION_BASE')) {
                    if (function_exists('curl_version')) {

                        // Get component params
                        //$componentParams = JComponentHelper::getParams('com_jappactivation');
                        //$paddleVendorID = $componentParams->get('paddle_vendor_id', NULL);
                        $paddleVendorID = env('PADDLE_VENDOR_ID');
                        $paddleVendorAuthCode = env('PADDLE_VENDOR_AUTH_CODE');



                        if (empty($paddleVendorID) || empty($paddleVendorAuthCode)) {
                            //JAppActivationHelper::log('Old Subscription cancellation failed!', JAppActivationLog::ERROR);
                            //JAppActivationHelper::log('Paddle vendor data not set properly, cannot send request!', JAppActivationLog::ERROR);
                            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $oldLicenseData->paddle_sid, JAppActivationLog::ERROR);
                            return false;
                        }

                        // Send subscription cancel request to Paddle
                        //JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models');
                       // $paddleModel = JModelLegacy::getInstance('Paddle', 'JAppActivationModel');

                        $decodedResponse = Paddle::cancelSubscription($oldLicenseData[0]['paddle_sid']);

                        if (!$decodedResponse || !isset($decodedResponse->success) || !$decodedResponse->success) {

                            //JAppActivationHelper::log('Old Subscription cancellation failed!', JAppActivationLog::ERROR);
                            if (!boolval($decodedResponse->success) && isset($decodedResponse->error)) {
                                if (isset($decodedResponse->error) && isset($decodedResponse->error->message)) {
                                    //JAppActivationHelper::log('-> Paddle says: ' . $decodedResponse->error->message, JAppActivationLog::ERROR);
                                }
                            }
                            //JAppActivationHelper::log('-> Paddle Subscription ID: ' . $oldLicenseData->paddle_sid, JAppActivationLog::ERROR);
                            return false;
                        }

                    } else {
                        //JAppActivationHelper::log('cURL library not installed or disabled!', JAppActivationLog::ERROR);
                        //JAppActivationHelper::log('Cannot cancel old subscription with Paddle Subscription ID: ' . $oldLicenseData->paddle_sid, JAppActivationLog::ERROR);
                        return false;
                    }
                    //JAppActivationHelper::log('Old license/subscription cancelled @ Paddle');
                }

                // Delete old license
                if(!MyLicenses::deleted($oldLicenseData[0]['id'])) {
                    //JAppActivationHelper::log('Deletion of old license/subscription failed!', JAppActivationLog::NOTICE);
                    return false;
                }
                //JAppActivationHelper::log('Old license/subscription deleted');
            }
        }
        else
        {
            //JAppActivationHelper::log('User wants to buy ' . $product->name . ' (Product ID: ' . $product->id . ')');

            // Check if product is in beta state
            if($product[0]['isbeta']) {
                //JAppActivationHelper::log('Purchase of products in beta prohibited!');
                return false;
            }

            // Detect buyer checkout mode (register or login)
            // Try to get buyer even if he wants to signup as a new user
            $passthroughData->email = 'dmitriy.yakynin@gmail.com';
            $buyer = \App\Http\Models\Front\Buyers\Buyers::buyerLookupByMail($passthroughData->email);

            if(empty($buyer)) {

                // No buyer found, try to create new
                if(isset($passthroughData->firstname)) {

                    //$passthroughData->email = 'Dima';
                   // $passthroughData->email = 'Yakunin';
                     $user_data = User::whereEmail('dmitriy.yakynin@gmail.com')->get()->toArray();
                    // Create new buyer
                    $buyerData = array(
                        'first' =>  $passthroughData->firstname,
                        'last' => $passthroughData->lastname,
                        'email' =>  $passthroughData->email,
                        'user_id' => $user_data[0]['id']
                    );

                    //$buyerID = $buyerModel->save($buyerData, true);
                    $buyerID = Buyers::create($buyerData);
                    if ($buyerID !== false) {
                        $buyer = $buyerID;
                    }
                }

                if(empty($buyer)) {
                    //JAppActivationHelper::log('Cannot create buyer with data:', JAppActivationLog::ERROR);
                    //JAppActivationHelper::log(json_encode($passthroughData));
                    return false;
                }
                //JAppActivationHelper::log('Buyer created with buyer ID: ' . $buyer->id);
               // JAppActivationHelper::log('Joomla user ID: ' . $buyer->user_id);
            }
        }

        // Create new license
        $licenseData = array();
        $licenseData['buyer_id'] = $buyer[0]['id'];
        $licenseData['product_id'] = $product[0]['id'];
        $licenseData['max_majver'] = $product[0]['default_majver'];
        $licenseData['date_purchase'] =  Carbon::now()->format('Y-m-d');


        // Set additional fields, depending on license type
        if(!$newProductIsSubscription) {
            $licenseData['type'] = env('LICENSE_TYPE_BASE');
            $licenseData['paddle_oid'] = $request->order_id;
            $licenseData['notes'] = sprintf('This license was remotely added by the Paddle checkout with the order ID: %s
Total was paid with %s.', $request->order_id, $request->payment_method);

            var_dump($licenseData); die();
        } else {
            $licenseData['date_activate'] = $licenseData['date_purchase'];
            $licenseData['type'] = env('SUBSCRIPTION_BASE');
            $licenseData['paddle_sid'] = $request->subscription_id;
            $licenseData['paddle_updateurl'] = $request->update_url;
            $licenseData['paddle_cancelurl'] = $request->cancel_url;
            $licenseData['paddle_status'] = $request->status;
            $licenseData['paddle_next_billdate'] = $request->next_bill_date;
            $licenseData['notes'] = sprintf('This subscription was remotely added by the Paddle checkout with the subscription ID: %s', $request->subscription_id);
        }

        // Take over old serial if upgrade
        $isiLokProduct = intval($product[0]['licsystem']) === env('LICENSE_SYSTEM_PACE');
        if($isiLokUpgrade)
        {
            if(!isset($legacyProductID)) {
                $licenseData['ilok_code'] = $passthroughData->upgradeiLok;
            }
        }
        else if($isLegacyUpgrade && !$isiLokProduct)
        {
            if(!isset($legacyProductID)) {
                $licenseData['serial'] = $passthroughData->upgradeSerial;
            }
        }
        $licenseData['notes'] .= 'This is an upgrade license';

        // Get purchased quantity if perpetual purchase
        $qty = 1;
        if(!$newProductIsSubscription) {
            $qty = intval($request->quantity);
            //JAppActivationHelper::log('-> Quantity: ' . $qty);
        }

        // Create x licenses
        for($i=0; $i<$qty; $i++) {

            // Add or reuse iLok code
            if ($isiLokProduct)
            {
                if(!$isiLokUpgrade)
                {
                    $licenseData['ilok_code'] = IlokCodes::getFreeCode($product[0]['id']);
                    if (empty($licenseData['ilok_code']))
                    {
                        //JAppActivationHelper::log('No more ilok codes on stock!!!', JLog::ERROR);
                       // $this->sendIlokStockWarningMail();
                        //$this->_finishFulfillment();
                        throw new Exception('Component error', 500);
                    }
                }
            }

            // Send a serial mail only, if no iLok upgrade is processed
            $sendSerialMail = !$isiLokUpgrade;
           // $result = $licenseModel->save($licenseData, true, $sendSerialMail);
            $result = License::create($licenseData);
            if(!$result){
                //JAppActivationHelper::log('Error creating new license!', JAppActivationLog::ERROR);
                //JAppActivationHelper::log('Received data: ' . json_encode($postData));
                return false;
            }

            if(!$newProductIsSubscription) {
                //JAppActivationHelper::log('New license created for buyer ' . $buyer->first . ' ' . $buyer->last);
                //JAppActivationHelper::log('-> License ID: ' . $result);
            } else {
               // JAppActivationHelper::log('New subscription created for buyer ' . $buyer->first . ' ' . $buyer->last);
                //JAppActivationHelper::log('-> License ID: ' . $result);
                //JAppActivationHelper::log('-> Subscription status: ' . $postData->status);
            }

            // Determine iLok -> iLok upgrade and just send an instruction email to the customer in this case
            if($isiLokUpgrade)
            {
               // JAppActivationHelper::sendIlokUpgradeMail($result);
               // JAppActivationHelper::log('iLok upgrade mail sent to customer', JLog::INFO);
            }
            else
            {
                // Invalidate iLok code (mark as used)
                if (isset($licenseData['ilok_code'])) {
                    if(intval($product->debug_mode) == 0) {
                        IlokCodes::consumeCode($licenseData['ilok_code']);
                        //JAppActivationHelper::log('Mmmhhhh: Used iLok code tastes perfect!', JLog::INFO);
                    } else {
                        //JAppActivationHelper::log('Debug mode enabled, will not eat iLok code!', JLog::INFO);
                    }
                }
            }


            // Reset model
            //$state = $licenseModel->getState();
            //$state->set($licenseModel->getName().'.id', 0);
        }

        return true;
    }


}
