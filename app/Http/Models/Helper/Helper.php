<?php

namespace App\Http\Models\Helper;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Helper extends Model
{
    /**
     * Converts bitmask to featureSet
     * @param   int         $featureMask    Unique value with feature options (bitmask)
     * @return  array       FeatureSet as array
     */
    public static function bitmask2feature($featureMask) {
        $mask = 0x1;
        $activeFeatures = array();

        $maxFeatureCount = env('MAX_PRODUCT_FEATURES');
        for ($i=0; $i<$maxFeatureCount; $i++) {
            if (intval($featureMask) & $mask)
                $activeFeatures[] = 1;
            else
                $activeFeatures[] = 0;

            $mask = $mask << 1;
        }

        return $activeFeatures;
    }


    /**
     * Calculate subscription expiration date
     * @param $purchaseDate     String      Purchase date as string
     * @return JDate     The expiration date
     */
    public static function getSubscriptionExpireDate($purchaseDate) {

        // Calculate the expire date
        $purchaseDateObject = Carbon::createFromDate($purchaseDate);
        $subscriptionAge = intval(self::getSubscriptionAge($purchaseDate));
        return $purchaseDateObject->modify('+' . ($subscriptionAge+1) . ' years');
    }

    /**
     * Calculates the age of a subscription
     *
     * @param $purchaseDate     String      Purchase date as string
     * @return string
     */
    public static function getSubscriptionAge($purchaseDate) {

        $purchaseDateObject = Carbon::createFromDate($purchaseDate);
        $interval = date_diff($purchaseDateObject, Carbon::now());
        return $interval->format('%y');
    }

    /**
     * Calculate expiration date
     * @param string     $start          The start date
     * @param int        $expireDays     Days till expiration
     * @return JDate     The expiration date
     */
    public static function getExpirationDate($start, $expireDays = 0) {

        // Calculate the expire date

        $startDate = Carbon::createFromDate($start);

        return $startDate->add('P'.$expireDays.'D');
    }

    public static function isExpired($start, $expireDays = 0) {

        $expireDate = self::getExpirationDate($start, $expireDays);
        $today = Carbon::now();
        $dateDiff = $today->diff($expireDate);
        return (intval($dateDiff->format('%r1')) < 0);   // Returns +1 or -1, depending if time left or not
    }

    /**
     * Converts the license type ID of the main types to the corresponding (string) name
     * @param  int      $licenseType    The license type ID to lookup
     * @return string   The license type name
     */
    public static function licenseTypeIDtoString($licenseTypeID) {

        $licenseTypeString = '';
        switch ($licenseTypeID) {

            case env('LICENSE_TYPE_BASE') :
                $licenseTypeString = 'Permanent license';
                break;

            case env('SUBSCRIPTION_BASE') :
                $licenseTypeString = 'Subscription';
                break;

            case env('SUBSCRIPTION_EXPIRED') :
                $licenseTypeString ='Subscription (expired)';
                break;

            case env('SUPPORTED_BASE') :
                $licenseTypeString = 'Supported license';
                break;

            case env('SUPPORT_EXPIRED') :
                $licenseTypeString = 'Supported license (expired)';
                break;

            case env('TEMP_BASE') :
                $licenseTypeString = 'Temporary license';
                break;

            case env('TEMP_EXPIRED') :
                $licenseTypeString = 'Temporary license (expired)';
                break;

            case env('LICENSE_TYPE_INVALID') :
                $licenseTypeString = 'Invalid license';
                break;

            default :
                break;
        }

        return $licenseTypeString;
    }

    /**
     * Returns the days until automatic subscription renewal
     *
     * @param $purchaseDate     String      Purchase date as string
     * @return string       Days until renewal
     */
    public static function getDaysTillSubscriptionRenewal($purchaseDate) {

        $expireDate = self::getSubscriptionExpireDate($purchaseDate);
        $interval = date_diff(Carbon::now(), $expireDate);
        return $interval->format('%a');
    }
}
