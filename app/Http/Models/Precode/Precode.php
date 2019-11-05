<?php

namespace App\Http\Models\Precode;

use App\Http\Models\License\License;
use App\Http\Models\Products\Products;
use Illuminate\Database\Eloquent\Model;

class Precode extends Model
{
    protected $fillable = ['product_id','data','precode','type','used','reference'];
    private static $allPreCodes = null;
    public static function generateFeaturePreActivationCodes($pid, $prefix, $licenseCount, $featureName) {

        // Get product
        $product = Products::getProductById($pid);
        // Pre-check product type
        if($product->type == env('SUBSCRIPTION_BASE')) {
            return false;
        }
        // Reset query before next iteration
        $preCodes = array();
        if(!empty($prefix)) {
            for($i=0; $i<$licenseCount; $i++) {
                $freshCode = License::generatePreCode($prefix);
                $preCodes[] = array(
                    'product_id' => $pid,
                    'precode' => trim(str_replace('-', '', $freshCode)),
                    'data' => json_encode(array('feature_name' => $featureName))
                );
            }

            // Write codes to database
            if(!empty($preCodes)) {
                try
                {
                    Precode::insert($preCodes);
                }
                catch(RuntimeException $e)
                {
                    return false;
                }
            }

            // Create and send pre-code email

            /*
            $mailer = JFactory::getMailer();
            $mailer->isHtml();
            $mailer->Encoding = 'base64';
            $mailer->addRecipient(JFactory::getConfig()->get('mailfrom'));

            $mailer->setSubject(JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_SUBJECT_FEATURE', $product->name, $featureName));
            $mailBody = JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_BODY_INTRO_FEATURE', $licenseCount, $product->name, $featureName);
            $mailBody .= '<p>' . JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_VALID_FOR', $product->default_majver, $product->code) . '</p>';
            $mailBody .= '<p><pre>' . implode('<br>', $preCodes) . '</pre></p>';

            // Send mail containing pre-activation codes to website owner (for security reasons no download)
            $mailer->setBody($mailBody);
            if(!$mailer->Send()) {
                return false;
            }
            */

        } else {
            return false;
        }

        return true;
    }


    public static function generatePreActivationCodes($pids, $licenseType, $licenseCount, $codeData = array(), $reference = '') {

        // Define license types
        $licenseTypes = array(
            1 => 'full',
            2 => 'upgrade',
            3 => 'temp'
        );
        $licenseSystems = array(
            1 => 'LL_LicenseLib',
            2 => 'PACE',
        );
/*
        // Prepare database
        $db = JFactory::getDbo();

        // Get products
        $query = $db->getQuery(true);
        $query->select('*')->from('#__jappactivation_products');
        $query->where('id IN (' . implode(',', $pids) . ')');
        $db->setQuery($query);
        $products = $db->loadAssocList();
*/
        $products = Products::whereIn('id',$pids)->get();

/*
        // Prepare insert statement
        $query = $db->getQuery(true);
        $query->insert('#__jappactivation_precodes');
        $query->columns('product_id, precode, type, data, reference');

        // Get license model for pre-code generation
        $licenseModel = JModelLegacy::getInstance('License', 'JAppActivationModel');

        // Prepare pre-code mail notification
        $mailer = JFactory::getMailer();
        $mailer->isHtml();
        $mailer->Encoding = 'base64';
        $mailer->addRecipient(JFactory::getConfig()->get('mailfrom'));
*/
        // Start code generation
        foreach($products as $product) {
            //dd($product);
            // Pre-check product type
            if($product->type == env('SUBSCRIPTION_BASE')) {
               // dd($product->type);
                //JFactory::getApplication()->enqueueMessage('Pre-codes for subscriptions not supported: ' . $product['name'] . ' (ID ' . $product['id'] . ')', 'error');
               // continue;
            }

            // Create codes only if appropriate Paddle PID is set
            $licenseTypeString = $licenseTypes[$licenseType];

            if($licenseType == 2) {
                if(empty($product->paddle_upgrade_pid)) {
                    //JFactory::getApplication()->enqueueMessage('No Paddle Upgrade-ID set, pre-code generation not possible: ' . $product['name'] . ' (ID ' . $product['id'] . ')', 'error');
                    //continue;
                }
            } else {
                if(empty($product->paddle_pid)) {
                   // JFactory::getApplication()->enqueueMessage('No Paddle Full-ID set, pre-code generation not possible: ' . $product['name'] . ' (ID ' . $product['id'] . ')', 'error');
                    //continue;
                }
            }

            $prefix = $product['prefix_' . $licenseTypeString];
            $preCodes = array();
            dd($prefix);
            // Reset query before next iteration
            $query->clear('values');
            if(!empty($prefix)) {
                for($i=0; $i<$licenseCount; $i++) {
                    $freshCode = $licenseModel->generatePreCode($prefix);
                    $preCodes[] = $freshCode;

                    // Create dataset values
                    $insertValues = array();
                    $insertValues[] = $product['id'];
                    $insertValues[] = $db->quote(JAppActivationHelper::cleanFormattedString($freshCode, '-'));
                    $insertValues[] = $licenseType;
                    $insertValues[] = $db->quote(json_encode($codeData));
                    $insertValues[] = $db->quote($reference);
                    $query->values(implode(',', $insertValues));
                }

                // Write codes to database
                if(!empty($preCodes)) {
                    $db->setQuery($query);

                    try
                    {
                        $db->execute();
                    }
                    catch(RuntimeException $e)
                    {
                        //JFactory::getApplication()->enqueueMessage('Error creating pre-codes for product: ' . $product['name'] . ' (ID ' . $product['id'] . ')', 'error');
                        continue;
                    }
                }
/*
                // Create and send pre-code email
                $licenseSystemString = $licenseSystems[intval($product['licsystem'])];
                $mailer->setSubject(JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_SUBJECT', JText::_('COM_JAPPACTIVATION_PRODUCT_LICENSE_SYSTEM_' . $licenseSystemString), $product['name']));
                $mailBody = JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_BODY_INTRO_MAIN', $licenseCount, $licenseSystemString, $product['name']);
                $mailBody .= '<p>' . JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_VALID_FOR', $product['default_majver'], $product['code']) . '</p>';
                $mailBody .= '<p>' . JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_REFERENCE', $reference) . '</p>';

                $mailBody .= JText::sprintf('COM_JAPPACTIVATION_PRECODE_MAIL_BODY_PRECODES_' . $licenseTypeString, isset($codeData['temp_days']) ? $codeData['temp_days'] : null);
                $mailBody .= '<p><pre>' . implode('<br>', $preCodes) . '</pre></p>';

                // Send mail containing pre-activation codes to website owner (for security reasons no download)
                $mailer->setBody($mailBody);
                if(!$mailer->Send()) {
                    JFactory::getApplication()->enqueueMessage('Error sending mail with pre-codes for product: ' . $product['name'] . ' (ID ' . $product['id'] . ')', 'error');
                    return false;
                } else {
                    JFactory::getApplication()->enqueueMessage('Mail with pre-codes for product ' . $product['name'] . ' (ID ' . $product['id'] . ') has been sent');
                }
*/
            } else {
                //JFactory::getApplication()->enqueueMessage(ucfirst($licenseTypeString) . ' pre-code prefix not set for product: ' . $product['name'] . ' (ID ' . $product['id'] . ')', 'error');
            }
        }

        return true;
    }



    public static function testPreActivationCode($preCode) {
        $preCode = trim(str_replace('-','',$preCode));
        // Retrieve all serials in database
        if(!self::$allPreCodes) {
            $all_precodes = Precode::pluck('precode')->toArray();
            self::$allPreCodes = $all_precodes;
        }
        // look for precode in precode list
        // if found, precode not valid, return false
        // if not found, add it dynamically to the precode list
        if(in_array($preCode, self::$allPreCodes)) {
            return false;
        } else {
            self::$allPreCodes[] = $preCode;
        }
        return true;
    }
}
