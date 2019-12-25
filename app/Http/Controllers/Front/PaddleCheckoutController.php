<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\License\License;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public static function ps_subscription_payment_succeeded(){

    }

    public static function ps_subscription_payment_failed(){

    }

    public static function ps_subscription_created(){

    }

    public static function ps_subscription_cancelled(Request $request){
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

    public static function ps_payment_succeeded(){

    }


}
