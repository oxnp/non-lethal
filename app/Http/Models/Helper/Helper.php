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

    public static function sendSerialMail($licenseID, $isIlokUpgrade = false) {
        /*
        // Abort, if no license was assigned
        if (!$licenseID)
            return false;

        // Obtain mail data
        $mailData = self::getMailData($licenseID);

        // Init the Mailer with sender
        JLoader::register('JAppActivationMailer', JPATH_COMPONENT_ADMINISTRATOR . '/classes/mailer.php');
        $mailer = new JAppActivationMailer();

        // Add sender and receivers
        $mailer->addRecipient($mailData->email);
        if ($mailData->ilok_code)
        {
            $config = JFactory::getConfig();
            $globalMailFrom = $config->get( 'fromname' );
            $globalMailAddress = $config->get( 'mailfrom' );

            $mailer->setSender(array($globalMailAddress, $globalMailFrom));
        }
        else
        {
            $mailer->addBCC($mailData->mail_bcc);
            $mailer->setSender(array($mailData->mail_address, $mailData->mail_from));
        }

        // Add buyer's BCC list
        if(!empty($mailData->bcc_emails)) {
            $cleanedBuyersBcc = preg_replace("/[\n\r ]/","",$mailData->bcc_emails);
            $bccReceivers = explode(',', $cleanedBuyersBcc);
            $mailer->addBCC($bccReceivers);
        }

        // Build license duration string
        $licenseDurationString = 'unlimited';
        if ($mailData->type == JAA_LICENSE_TYPE::SUPPORTED_BASE)
            $licenseDurationString = $mailData->support_days.' days';
        elseif ($mailData->type == JAA_LICENSE_TYPE::TEMP_BASE)
            $licenseDurationString = $mailData->license_days.' days';

        // Substitute body text fields
        $mailer->setSubFieldKeys(array(
            '[product.name]',
            //'[product.options]',
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
        ));
        $mailer->setSubFieldValues(array(
            $mailData->name,
            //JAppActivationHelper::featureNamesAsList($mailData->product_id, $mailData->options),
            $mailData->last,
            $mailData->first,
            $mailData->company,
            self::getFormattedString($mailData->serial, 5, '-'),
            $mailData->ilok_code,
            $mailData->max_majver,
            self::licenseTypeIDtoString($mailData->type),
            $licenseDurationString,
            $mailData->seats,
            '<a href="' . JUri::root() . '">' . JUri::root() . '</a>'
        ));

        // Set subject and body
        if ($mailData->ilok_code)
        {
            // Assign global defined iLok texts (PACE activation)
            $params = JComponentHelper::getParams('com_jappactivation');
            if ($isIlokUpgrade)
            {
                $iLokMailSubject = $params->get('ilok_upgrade_mail_subject');
                $iLokMailBody = $params->get('ilok_upgrade_mail_body');
            }
            else
            {
                $iLokMailSubject = $params->get('ilok_send_mail_subject');
                $iLokMailBody = $params->get('ilok_send_mail_body');
            }

            $mailer->setSubject($iLokMailSubject);
            $mailer->setBody($iLokMailBody);
        }
        else
        {
            // Assign texts attached to the product (LL_LicenseLib activation)
            $mailer->setSubject($mailData->mail_subject);
            $mailer->setBody($mailData->mail_body);
        }


        // Send mail
        if( !$mailer->Send() ) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_JAPPACTIVATION_LICENSE_EMAIL_NOT_SENT').' '.$mailData->email, 'notice');
            return false;
        }

        // Append admin message
        JFactory::getApplication()->enqueueMessage(JText::_('COM_JAPPACTIVATION_LICENSE_EMAIL_SENT').' '.$mailData->email, 'message');

        if(isset($bccReceivers) && !empty($bccReceivers)) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_JAPPACTIVATION_LICENSE_EMAIL_SENT_BCC').' '.implode(', ', $bccReceivers), 'message');
        }
   */
        return true;

    }

}
