<?php

namespace App\Http\Models\Front\MyLicenses;

use App\Http\Models\Front\Precode\Precode;
use App\Http\Models\Front\Products\Products;
use App\Http\Models\Front\Users\UserRole;
use App\Http\Models\Helper\Helper;
use App\Http\Models\License\License;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Front\Buyers\Buyers;
use App\Http\Models\Front\MyLicenses\Seats;
use Auth;
use DB;
use Exception;

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

    public static function getDownloadLinks($id){

        $result['latest'] = array();
        $result['legacy'] = array();

        $array_products_dir = [
            '30' => 'adrmaster',
            '33' => 'adrmaster',
            '34' => 'adrmaster',
            '38' => 'adrmaster',
            '31' => 'adrmaster',
            '35' => 'adrmaster',
            '36' => 'adrmaster',
            '37' => 'adrmaster',
            '39' => 'adrmaster',
            '3' => 'videoslave2',
            '9' => 'videoslave3',
            '11' => 'videoslave3',
            '23' => 'videoslave3',
            '24' => 'videoslave3',
            '25' => 'videoslave3',
            '10' => 'videoslave3',
            '22' => 'videoslave3',
            '28' => 'videoslave4',
            '29' => 'videoslave4',
            '32' => 'videoslave4',
            '28' => 'videoslave4'
        ];
        $path_latest = $_SERVER['DOCUMENT_ROOT'].'/public/nla_files/latest_version/';
        $path_legacy = $_SERVER['DOCUMENT_ROOT'].'/public/nla_files/';

        $i=0;

        try {
            $files = scandir($path_latest . $array_products_dir[$id]);
        }catch (Exception $e){
            return $e;
        }

        $tmp_array = array();
        unset($files[0]);
        unset($files[1]);

        foreach($files as $file){
            if (substr($file,0,1) != '.') {
                if(!strpos($file,'html')) {
                    $tmp_array[$i]['zip']['link'] = '/nla_files/latest_version/' . $array_products_dir[$id] . '/' . $file;
                    $tmp_array[$i]['zip']['name'] = substr($file,0,-4);

                    $tmp_array[$i]['changelog'] = '/nla_files/latest_version/' . $array_products_dir[$id] . '/' . substr($file,0,-4).'_changelog.html';
                    //$tmp_array[$i]['file']['changelog']['name'] = $file;
                }
                }
            $i++;
        }

        $tmp_array_legacy = array();
        $files = scandir($path_legacy.$array_products_dir[$id]);
        unset($files[0]);
        unset($files[1]);
        foreach($files as $file){
            if (substr($file,0,1) != '.') {
                if(!strpos($file,'html')) {
                $tmp_array_legacy[$i]['zip']['link'] = '/nla_files/'.$array_products_dir[$id].'/'.$file;
                $tmp_array_legacy[$i]['zip']['name'] = substr($file,0,-4);
                $tmp_array_legacy[$i]['changelog'] = '/nla_files/' . $array_products_dir[$id] . '/' . substr($file,0,-4).'_changelog.html';
            }
            }
            $i++;
        }

        $result['latest'] = $tmp_array;
        $result['legacy'] = $tmp_array_legacy;
        return $result;
    }
    public static function getLicensesByUser(){
        $data = array();


        $buyer_data = Buyers::whereUserId(Auth::ID())->get()->toArray();

        if (!isset($buyer_data[0]['id'])){
            return $data;
        }

        $product_ids = MyLicenses::whereBuyerId($buyer_data[0]['id'])->select('product_id')->groupBy('product_id')->get();
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
            'licenses.paddle_queue_cancel',
            'ntl.user_notes')
            ->leftjoin('seats as s','s.license_id','licenses.id')
            ->leftjoin('notes_to_license as ntl','ntl.license_id','licenses.id')
            ->leftjoin('buyers as b','licenses.buyer_id','b.id')
            ->where('b.user_id',Auth::ID())
            ->groupBy('licenses.id')
            ->get()
            ->toArray();



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

                    $licSystem = $license['ilok_code'] ? 'PACE' : 'NLA Licensing';
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
                            $type .= '<br><a href="' . route('queueCancelSubscription',['licenseid'=>(int)$license['id']]). '" class="btn_subscription_queue_cancel"><i class="fa fa-trash-o"></i> Cancel subscription</a>';
                        }

                    } else {
                        $type .= '<br><b>This license will end on ' . Helper::getSubscriptionExpireDate($purchaseDate)->format('Y-m-d') . '</b>';
                    }
                }

                if ($license['seats'] > 1) {
                    $type .= '<i class="fa fa-files-o hasTip" style="margin-left: 7px;" title="Multi-Seat license"></i>';
                }
                //type

                    $data[$product['name']]['downloads_links'] = self::getDownloadLinks($product['id']);

                    $data[$product['name']]['licenses'][$i]['ilok'] = $license['ilok_code'];
                    $data[$product['name']]['licenses'][$i]['license_id'] = $license['id'];
                    $data[$product['name']]['licenses'][$i]['user_notes'] = $license['user_notes'];
                    $data[$product['name']]['licenses'][$i]['serial'] =  substr(chunk_split($license['serial'], 5, '-'), 0, -1);
                    $data[$product['name']]['licenses'][$i]['type'] = $type;
                    $data[$product['name']]['licenses'][$i]['purchase_date'] = Date('Y-m-d', strtotime($purchaseDate));
                    $data[$product['name']]['licenses'][$i]['expire_date'] = $exp_date;
                    $data[$product['name']]['licenses'][$i]['notes'] = $license['notes'];
                    $data[$product['name']]['licenses'][$i]['product_id'] = $product['id'];
                    $data[$product['name']]['licenses'][$i]['status_title'] = $statusTitle;
                    $data[$product['name']]['licenses'][$i]['status'] = $status;

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
                        $data[$product['name']]['licenses'][$i]['upgrade_targets'] = $tmp_upgrade;
                        $data[$product['name']]['licenses'][$i]['select_id'] = $dropdownID;

                    }else {
                        $data[$product['name']][$i]['upgrade_targets'] = 'Upgrading multi-seat licenses is not supported at the moment. Please contact us for assistance.';
                    }

                }
                $i++;
            }
        }

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

    public static function lookupByPreCode($precode){

        $result = Precode::where('precode',$precode)
            ->leftjoin('products as p','p.id','precodes.product_id')
            ->get()->toArray();

        // Detect precode state, return false if already consumed
        if(!empty($result)) {
            if (intval($result[0]['used']) == 1) {
                return false;
            }
        }

        return $result;
    }

    public static function deleted($pks) {
        // Make sure, pks is an array
        $pks = (array)$pks;
        License::whereIn('licenses.id',implode(',', $pks))
            ->leftjoin('seats as s','s.license_id','licenses.id')->delete();
    }


}
