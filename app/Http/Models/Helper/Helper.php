<?php

namespace App\Http\Models\Helper;

use App\Jobs\SendEmail;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Http\Models\License\License;
use App\Http\Models\EmailsTemplates\EmailsTemplates;
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
     * Converts featureSet to bitmask
     * @param   array()     $featureSet   Featureset of a product
     * @return  int         FeatureSet as value (bitmask)
     */
    public static function feature2bitmask($featureSet) {
        $featureMask = 0;

        foreach ($featureSet as $featureBit) {
            $featureMask += pow(2, intval($featureBit));
        }

        return $featureMask;
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

    public static function sendIlokStockWarning($productName)
    {
        /*
        // Init the Mailer with sender
        JLoader::register('JAppActivationMailer', JPATH_COMPONENT_ADMINISTRATOR . '/classes/mailer.php');
        $mailer = new JAppActivationMailer();

        // Add sender and receiver
        $config = JFactory::getConfig();
        $globalMailAddress = $config->get('mailfrom');
        $globalMailFrom = $config->get('fromname');
        $mailer->setSender(array($globalMailAddress, $globalMailFrom));
        $mailer->addRecipient($globalMailAddress);

        // Assign texts
        $mailer->setSubject(JText::sprintf('COM_JAPPACTIVATION_ILOK_STOCK_WARNING_MAIL_SUBJECT', $productName));
        $mailer->setBody(JText::sprintf('COM_JAPPACTIVATION_ILOK_STOCK_WARNING_MAIL_BODY', $productName));

        return $mailer->Send();
        */
    }
    public static function sendIlokUpgradeMail($licenseID)
    {
        self::sendSerialMail($licenseID, true);
    }

    public static function getMailData($licenseID) {

        $license_data =  License::select('licenses.id',
            'licenses.serial',
            'licenses.ilok_code',
            'licenses.seats',
            'licenses.max_majver',
            'licenses.type',
            'licenses.support_days',
            'licenses.license_days',
            'b.first',
            'b.last',
            'b.company',
            'b.email',
            'b.bcc_emails',
            'p.name',
            'p.mail_address',
            'p.mail_from',
            'p.mail_bcc',
            'p.mail_subject',
            'p.mail_body')
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->leftjoin('products as p','p.id','licenses.product_id')
            ->where('licenses.id',$licenseID)
            ->get();

        return $license_data;
    }

    public static function sendSerialMail($licenseID, $isIlokUpgrade = false) {
        if (!$licenseID)
            return false;
        // Obtain mail data
        $mailData = self::getMailData($licenseID);

        $sender_info = array();
        $recepient_info = array();
        $recepient_info['email'] = $mailData[0]->email;

        if ($mailData[0]->ilok_code){
            $sender_info['name_from'] = env('MAIL_FROM_NAME');
            $sender_info['email_from'] =  env('MAIL_FROM_ADDRESS');
            $sender_info['email_reply'] = env('MAIL_FROM_ADDRESS');
            $sender_info['subject'] = '';
        }else{

        }

        // Build license duration string
        $licenseDurationString = 'unlimited';
        if ($mailData[0]->type == env('SUPPORTED_BASE'))
            $licenseDurationString = $mailData[0]->support_days.' days';
        elseif ($mailData[0]->type == env('TEMP_BASE'))
            $licenseDurationString = $mailData[0]->license_days.' days';


        $fields = [
            '[product.name]',
            '[customer.last]',
            '[customer.first]',
            '[customer.company]',
            '[license.serial]',
            '[license.ilok_code]',
            '[license.majorversion]',
            '[license.type]',
            '[license.duration]',
            '[license.seats]',
            '[website]',
        ];

        $fields_replace = [
            $mailData[0]->name,
            $mailData[0]->last,
            $mailData[0]->first,
            $mailData[0]->company,
            substr(chunk_split( $mailData[0]->serial,5,'-'),0,-1),
            $mailData[0]->ilok_code,
            $mailData[0]->majorversion,
            self::licenseTypeIDtoString($mailData[0]->type),
            $licenseDurationString,
            $mailData[0]->seats,
            env('APP_URL'),
        ];


        // Set subject and body
        if ($mailData[0]->ilok_code) {

            if ($isIlokUpgrade)
            {
                $template = EmailsTemplates::where('alias_name','ilok_update')->get();

                $recepient_info['body_html'] = str_replace($fields,$fields_replace,$template[0]->body_html);

                $sender_info['name_from'] = $template[0]->from_name;
                $sender_info['email_from'] =  $template[0]->from_addres;
                $sender_info['email_reply'] = $template[0]->reply_to_addres;
                $sender_info['subject'] = str_replace($fields,$fields_replace,$template[0]->subject);
            }else{
                $template = EmailsTemplates::where('alias_name','ilok')->get();

                $recepient_info['body_html'] = str_replace($fields,$fields_replace,$template[0]->body_html);

                $sender_info['name_from'] = $template[0]->from_name;
                $sender_info['email_from'] =  $template[0]->from_addres;
                $sender_info['email_reply'] = $template[0]->reply_to_addres;
                $sender_info['subject'] = str_replace($fields,$fields_replace,$template[0]->subject);
            }
        }else{
            $sender_info['subject'] = str_replace($fields,$fields_replace,$mailData[0]->mail_subject);
            $recepient_info['body_html'] = str_replace($fields,$fields_replace,$mailData[0]->mail_body);

            $sender_info['name_from'] = $mailData[0]->mail_from;
            $sender_info['email_from'] =  $mailData[0]->mail_address;
            $sender_info['email_reply'] = $mailData[0]->mail_address;
        }

        $job = dispatch(new SendEmail($recepient_info,$sender_info));

        return true;
    }

}
