<?php

namespace App\Http\Models\Front\MyLicenses;

use App\Http\Models\Front\Products\Products;
use App\Http\Models\Front\Users\UserRole;
use App\Http\Models\Helper\Helper;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Front\Buyers\Buyers;
use App\Http\Models\Front\MyLicenses\Seats;
use Auth;
use DB;

class MyLicenses extends Model
{
    protected $table = "licenses";

    public static function getTest(){
        $licenses = MyLicenses::select('licenses.id AS license_id',
            'licenses.product_id AS product_id',
            'licenses.type AS license_type',
            'licenses.serial AS license_serial',
            'licenses.ilok_code AS license_ilok_code',
            'licenses.seats AS license_seats',
            'licenses.license_days AS license_temp_days',
            'licenses.support_days AS license_support_days',
            'licenses.date_purchase AS license_date_purchase',
            'licenses.date_activate AS license_date_activate',
            'licenses.paddle_sid',
            'licenses.paddle_status',
            'licenses.paddle_next_billdate',
            'licenses.paddle_cancelurl',
            'licenses.paddle_updateurl',
            'licenses.paddle_queue_cancel',
            DB::raw('COUNT(s.id) AS active_seatcount'))
            ->leftjoin('seats as s','s.license_id','licenses.id')
            ->leftjoin('buyers as b','b.id','licenses.buyer_id')
            ->groupBy('licenses.id')
            ->orderBy('licenses.date_purchase')->where('b.user_id',2792)->get()->toArray();


        $products = Products::select('products.id AS product_id',
            'products.ordering AS product_ordering',
            'products.name AS product_name',
            'products.code AS product_code',
            'products.isbeta AS product_isbeta',
            'products.default_majver AS product_majorversion')
            ->join('licenses AS l', 'l.product_id','products.id')
            ->join('buyers AS b','l.product_id','products.id')
            ->groupBy('products.id')
            ->orderBy('products.ordering','ASC')->where('b.user_id',2792)->get()->toArray();


        // Group licenses by products
        foreach ($products as $product) {

            $product['licenses'] = array();

            foreach ($licenses as $key => $license) {
                if ($license['product_id'] == $product['product_id']) {
                    $product['licenses'][] = $license;
                    unset($licenses[$key]);
                }
            }

            // Get upgrade targets
            $product['upgrade_targets'] = self::getUpgradeTargets($product['product_id']);
        }

        dd($product);

        foreach($products as $product) {
            foreach($product['licenses'] as $license) {

                // Detect real license type
                $licenseType = $license['license_type'];
                $purchaseDate = $license['license_date_purchase'];
                $activationDate = isset($license['license_date_activate']) ? $license['license_date_activate'] : null;
                $tempDays = isset($license['license_temp_days']) ? $license['license_temp_days'] : null;
                $supportDays = isset($license['license_support_days']) ? $license['license_support_days'] : null;

            }
        }
    }


    public static function getLicensesByUser(){

        $product_ids = MyLicenses::whereBuyerId(93)->select('product_id')->groupBy('product_id')->get();


        $ids = $product_ids->pluck('product_id')->toArray();


        $products = Products::whereIn('id',$ids)->get()->toArray();


        $licenses = MyLicenses::select(DB::raw('COUNT(s.id) AS active_seatcount'),
            'licenses.id',
            'licenses.serial',
            'licenses.ilok_code',
            'licenses.product_id',
            'licenses.type',
            'licenses.date_purchase',
            'licenses.seats',
            'licenses.notes',
            'licenses.license_days',
            'licenses.date_activate',
            'licenses.paddle_cancelurl',
            'licenses.paddle_sid',
            'licenses.paddle_status',
            'licenses.paddle_updateurl',
            'licenses.paddle_queue_cancel')
            ->leftjoin('seats as s','s.license_id','licenses.id')
            ->leftjoin('buyers as b','licenses.buyer_id','b.id')
            ->where('b.user_id',816)
            ->groupBy('licenses.id')
            ->get()
            ->toArray();


        $data = array();
        foreach($products as $product) {
            $i = 0;

            foreach ($licenses as $license) {
                if ($license['product_id'] == $product['id']) {

                    $licenseType = $license['type'];
                    $purchaseDate = $license['date_purchase'];
                    $activationDate = isset($license['date_purchase']) ? $license['date_purchase'] : null;
                    $tempDays = isset($license['license_days']) ? $license['license_days'] : null;
                    $supportDays = isset($license['support_days']) ? $license['support_days'] : null;

//status

                    if ($license['ilok_code'])
                    {
                        $status = 'ilok';
                    }
                    else
                    {
                        switch ($licenseType) {

                            case env('LICENSE_TYPE_BASE') :
                            case env('TEMP_BASE') :
                            case env('SUPPORTED_BASE') :

                                $status = ($license['active_seatcount'] > 0) ? 'active' : 'inactive';
                                break;

                            case env('SUBSCRIPTION_BASE'):
                                $status = $license['paddle_status'];

                                // Get activation status according to active seats
                                if ($status == 'active') {
                                    $status = ($license['active_seatcount'] > 0) ? 'active' : 'inactive';
                                } elseif (empty($status)) {
                                    $status = ('paddle_unknown');
                                }

                                break;

                            case env('LICENSE_TYPE_INVALID'):
                            case env('SUBSCRIPTION_EXPIRED'):
                            case env('SUPPORT_EXPIRED'):
                            case env('TEMP_EXPIRED'):
                                $status = 'expired';
                                break;

                            default:

                                break;
                        }
                    }

                    $licSystem = $license['ilok_code'] ? 'PACE' : 'LL_LICENSELIB';
                    $statusTitle = 'License system:<br><b>' .$licSystem . '</b><br>';

                    $statusTitle .= 'Status:<br><b>' . $status . '</b>';
                    if($license['seats'] > 1) {
                        $statusTitle .= '<br>Seats used: <b>' . $license['active_seatcount'] . ' / ' . $license['seats'] . '</b>';
                    }

//status


                    //type
                    switch ($licenseType) {

                        case env('TEMP_BASE') :              // Check if temporary license is expired
                            $isExpired = Helper::isExpired($activationDate, $tempDays);
                            if ($isExpired) {
                                $licenseType = env('TEMP_EXPIRED');
                            }
                            break;

                        case env('SUPPORTED_BASE') :         // Check if supported license is expired
                            $isExpired = Helper::isExpired($purchaseDate, $supportDays);
                            if ($isExpired) {
                                $licenseType = env('SUPPORT_EXPIRED');
                            }
                            break;

                        case  env('SUBSCRIPTION_BASE') :      // Check if subscription license is expired
                            if ($license['paddle_status'] == env('PADDLE_STATUS_DELETED')) {
                                $licenseType = env('SUBSCRIPTION_EXPIRED');
                            }
                            break;

                        default:
                            break;
                    }

                    // Improves code readability...
                    $isSubscription = ($licenseType == env('SUBSCRIPTION_BASE'));

                    $expirationDate = null;
                    if ($isSubscription) {
                        $expirationDate = Helper::getSubscriptionExpireDate($purchaseDate);
                    } elseif ($licenseType == env('TEMP_BASE')) {
                        $expirationDate = Helper::getExpirationDate($activationDate, $tempDays);
                    } elseif ($licenseType == env('SUPPORTED_BASE')) {
                        $expirationDate = Helper::getExpirationDate($purchaseDate, $supportDays);
                    }

                    if (!empty($expirationDate)) {
                        $exp_date = Date('Y-m-d', strtotime($expirationDate));
                    } else {
                        $exp_date = '-';
                    }

                $type = '';
                if ($license['ilok_code']) {
                    $type = 'PACE iLok license';
                } else {
                    $type .= Helper::licenseTypeIDtoString($licenseType);
                }

                if ($isSubscription) {
                    $type .= '<br><a target="_blank" href="' . $license['paddle_updateurl'] . '" class="btn_subscription_update"><i class="fa fa-usd"></i> Payment method </a>';

                    // Display cancel buttons only, if not already cancelled or in queue
                    if (intval($license['paddle_queue_cancel']) == 0) {
                        $daysTillSubRenewal = Helper::getDaysTillSubscriptionRenewal($purchaseDate);

                        if ($daysTillSubRenewal <= 30) {
                            $type .= '<br><a href="' . $license['paddle_cancelurl'] . '" data-paddle_sid="' . $license['paddle_sid'] . '" class="btn_subscription_cancel"><i class="fa fa-trash-o"></i> Cancel subscription</a>';
                        } else {
                            $type .= '<br><a href="' . 'index.php?option=com_jappactivation&task=paddle.queueDelete&licenseid=' . (int)$license['id'] . '" class="btn_subscription_queue_cancel"><i class="fa fa-trash-o"></i> Cancel subscription</a>';
                        }

                    } else {
                        $type .= '<br><b>This license will end on ' . Helper::getSubscriptionExpireDate($purchaseDate)->format('Y-m-d') . '</b>';
                    }
                }

                if ($license['seats'] > 1) {
                    $type .= '<i class="fa fa-files-o hasTip" style="margin-left: 7px;" title="Multi-Seat license"></i>';
                }
                //type

                    $data[$product['name']][$i]['ilok'] = $license['ilok_code'];
                    $data[$product['name']][$i]['serial'] =  substr(chunk_split($license['serial'], 5, '-'), 0, -1);
                    $data[$product['name']][$i]['type'] = $type;
                    $data[$product['name']][$i]['purchase_date'] = Date('Y-m-d', strtotime($purchaseDate));
                    $data[$product['name']][$i]['expire_date'] = $exp_date;
                    $data[$product['name']][$i]['notes'] = $license['notes'];
                    $data[$product['name']][$i]['product_id'] = $product['id'];
                    $data[$product['name']][$i]['status'] = $statusTitle;

                    if($license['seats'] <= 1) {

                        if (!isset($product['isbeta']) || !boolval($product['isbeta'])) {
                            switch ($licenseType) {
                                case env('LICENSE_TYPE_BASE'):
                                case env('SUBSCRIPTION_BASE'):
                                $tmp_target = self::getUpgradeTargets($product['id']);

                                // Generate upgrade target dropdown
                                $upgradeTargetArray = array();
                                foreach($tmp_target as $upgradeTarget) {

                                    $reqAccessLevel = intval($upgradeTarget['access_level']);
                                    if($reqAccessLevel == 0) {
                                        $reqAccessLevel =  intval(env('default_accesslevel'));
                                    }
                                    $userAccessLevels = UserRole::getAuthorisedViewLevels();
                                    if(in_array($reqAccessLevel, $userAccessLevels)) {
                                        if(!boolval($upgradeTarget['isbeta'])) {
                                            if(!empty($upgradeTarget['paddle_upgrade_pid'])) {
                                                $options = array();
                                                $options['value'] = $upgradeTarget['paddle_upgrade_pid'];
                                                $options['text'] = $upgradeTarget['product_name'];

                                                if($upgradeTarget['product_type'] == env('SUBSCRIPTION_BASE')) {
                                                    $options['attr'] = array(
                                                        'data-subscription' => 'true'
                                                    );
                                                }
                                                $upgradeTargetArray[] = $options;
                                            }
                                        }
                                    }
                                }
                                $dropdownID = $license['id'] . '_upgrade_select';
                                if((count($upgradeTargetArray) > 0) && intval($license['paddle_queue_cancel']) == 0) {
                                    // Prepend some default list entries
                                    $defaultEntries = [
                                        array('value' => 0, 'text' => '--- Select to start upgrade ---'),
                                        array('value' => 'code', 'text' => 'Upgrade with pre-activation code'),
                                        array('value' => '', 'text' => '', 'disable' => true)
                                    ];
                                    $upgradeTargetArray = array_merge($defaultEntries, $upgradeTargetArray);

                                    $tmp_upgrade = $upgradeTargetArray;
                                }else{
                                 //   dd($product['name']);
                                    $tmp_upgrade = 'No upgrades available';
                                }

                                break;

                                case env('TEMP_BASE') :
                                case env('SUPPORTED_BASE') :
                                $tmp_upgrade = 'Upgrades only available for perpetual licenses. <a href ="'. $product['upgrade_link_page'].'">Please subscribe to a new plan</a>';
                                    //echo(JText::sprintf('COM_JAPPACTIVATION_USER_LICENSES_UPGRADES_ONLY_PERPETUAL_LINK', JRoute::_('index.php?Itemid=' . $productPurchaseMenuItemid)));
                                    break;

                                case env('LICENSE_TYPE_INVALID')  :
                                case env('SUBSCRIPTION_EXPIRED') :
                                case env('SUPPORT_EXPIRED') :
                                case env('TEMP_EXPIRED') :
                                $tmp_upgrade = 'This license has expired! <a href ="'. $product['upgrade_link_page'].'">Please subscribe to a new plan</a>';
                                   // echo(JText::sprintf('COM_JAPPACTIVATION_USER_LICENSES_EXPIRED_PLEASE_UPGRADE_LINK', JRoute::_('index.php?Itemid=' . $productPurchaseMenuItemid)));
                                    break;

                                default :
                                    break;

                            }
                        }
                        $data[$product['name']][$i]['upgrade_targets'] = $tmp_upgrade;
                        $data[$product['name']][$i]['select_id'] = $dropdownID;
                    }else {
                        $data[$product['name']][$i]['upgrade_targets'] = 'Upgrading multi-seat licenses is not supported at the moment. Please contact us for assistance.';
                    }

                }
                $i++;
            }
        }
//dd($data);
        return $data;
    }

    public static function getUpgradeTargets($productID)
    {
        $product = Products::select('id AS product_id',
            'name AS product_name',
            'paddle_upgrade_pid',
            'isbeta',
            'type AS product_type',
            'access AS access_level')
            ->where('upgradeable_products','LIKE','%"' . $productID . '"%')
            ->where('published',1)->get()->toArray();
        return $product;
    }

}
