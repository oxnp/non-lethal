<?php

namespace App\Http\Models\Front\MyLicenses;

use App\Http\Models\Front\Products\Products;
use App\Http\Models\Helper\Helper;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class MyLicenses extends Model
{
    protected $table = "licenses";
    public static function getLicensesByUser(){

        $product_ids = MyLicenses::whereBuyerId(93)->select('product_id')->groupBy('product_id')->get();
        $ids = $product_ids->pluck('product_id')->toArray();
        $products = Products::whereIn('id',$ids)->get()->toArray();
        $licenses = MyLicenses::whereBuyerId(93)->select('id','serial','ilok_code','product_id','type','date_purchase','seats','notes','license_days','date_activate','paddle_cancelurl','paddle_sid','paddle_status','paddle_updateurl','paddle_queue_cancel')->get()->toArray();

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
                    /*
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

                                $status = ($license->active_seatcount > 0) ? 'active' : 'inactive';
                                break;

                            case JAA_LICENSE_TYPE::SUBSCRIPTION_BASE :
                                $status = $license->paddle_status;

                                // Get activation status according to active seats
                                if ($status == 'active') {
                                    $status = ($license->active_seatcount > 0) ? 'active' : 'inactive';
                                } elseif (empty($status)) {
                                    $status = ('paddle_unknown');
                                }

                                break;

                            case JAA_LICENSE_TYPE::INVALID :
                            case JAA_LICENSE_TYPE::SUBSCRIPTION_EXPIRED :
                            case JAA_LICENSE_TYPE::SUPPORT_EXPIRED :
                            case JAA_LICENSE_TYPE::TEMP_EXPIRED :
                                $status = 'expired';
                                break;

                            default:

                                break;
                        }
                    }

                    $licSystem = $license->license_ilok_code ? 'PACE' : 'LL_LICENSELIB';
                    $statusTitle = 'License system:<br><b>' . JText::_('COM_JAPPACTIVATION_USER_LICENSES_SYSTEM_' . $licSystem) . '</b><br>';

                    $statusTitle .= 'Status:<br><b>' . JText::_('COM_JAPPACTIVATION_USER_LICENSES_STATUS_' . $status) . '</b>';
                    if($license->license_seats > 1) {
                        $statusTitle .= '<br>Seats used: <b>' . $license->active_seatcount . ' / ' . $license->license_seats . '</b>';
                    }
                    */
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
                            $isExpired = JAppActivationHelper::isExpired($purchaseDate, $supportDays);
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
                        $type .= '<br><b>This license will end on ' . JAppActivationHelper::getSubscriptionExpireDate($purchaseDate)->format('Y-m-d') . '</b>';
                    }
                }

                if ($license['seats'] > 1) {
                    $type .= '<i class="fa fa-files-o hasTip" style="margin-left: 7px;" title="Multi-Seat license"></i>';
                }
                //type

                    $data[$product['name']][$i]['serial'] = $license['serial'] == '' ? $license['ilok_code'] : substr(chunk_split($license['serial'], 5, '-'), 0, -1);
                    $data[$product['name']][$i]['type'] = $type;
                    $data[$product['name']][$i]['purchase_date'] = Date('Y-m-d', strtotime($purchaseDate));
                    $data[$product['name']][$i]['expire_date'] = $exp_date;
                    $data[$product['name']][$i]['select_upgrade'] = 'to do';
                    $data[$product['name']][$i]['notes'] = $license['notes'];
                    $data[$product['name']][$i]['product_id'] = $product['id'];
                }
                $i++;
            }
        }

        return $data;
    }
}
