@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

    <div class="pads">
        <form name="search" method="GET" action="">
            <input value="{{$filter['search_string']}}" class="form-control" type="text" name="searchstring" placeholder="Search..."/>
            <input type="submit" class="btn btn-primary" value="Search"/>
        </form>
    </div>

    <div class="list">
        <div class="list_head">
            <div class="col-md-1">ID</div>
            <div class="col-md-2">Serial/iLok Code</div>
            <div class="col-md-2">Buyer Name</div>
            <div class="col-md-2">Product name</div>
            <div class="col-md-1">Max version</div>
            @if($filter['sort']=='ASC')
                <div class="col-md-1">
                    <a href="?searchstring={{$filter['search_string']}}&orderby=licenses.date_purchase&sort=DESC">Purchase date</a>
                </div>
                <div class="col-md-1">
                    <a href="?searchstring={{$filter['search_string']}}&orderby=licenses.date_activate&sort=DESC">Activation date</a>
                </div>
            @else
                <div class="col-md-1">
                    <a href="?searchstring={{$filter['search_string']}}&orderby=licenses.date_purchase&sort=ASC">Purchase date</a>
                </div>
                <div class="col-md-1">
                    <a href="?searchstring={{$filter['search_string']}}&orderby=licenses.date_activate&sort=ASC">Activation date</a>
                </div>
            @endif
            <div class="col-md-1">Expiry date</div>
            <div class="col-md-1">Usage</div>
        </div>
        <div class="list_body">
            @foreach($licenses as $license)
                <div class="item">
                    <div class="col-md-1 idcol"><input type="checkbox" value="{{$license['id']}}" name="pre_id"/>{{$license['id']}}</div>
                    <div class="col-md-2"> <a href="{{route('licenses.show',$license['id'])}}">@if($license['ilok_code']) {{$license['ilok_code']}} @else{{substr(chunk_split($license['serial'],5,'-'),0,-1)}}@endif</a></div>
                    <div class="col-md-2">{{$license['last']}} {{$license['first']}}</div>
                    <div class="col-md-2">{{$license['name']}}
                        @if(!empty($license['prod_features']))
                        @php
                        $productFeatures = explode(',', $license['features']);
                        $purchasedFeatures = Helper::bitmask2feature($license['prod_features']);
                        $featureList = array();
                        foreach($purchasedFeatures as $key => $enabled) {
                            if($enabled)
                                $featureList[] = $productFeatures[$key];
                        }
                        @endphp
                            {{ '('.(implode(', ', $featureList)).')'}}
                        @endif

                    </div>
                    <div class="col-md-1">{{$license['max_majver']}}.x</div>
                    <div class="col-md-1">{{date('Y-m-d', strtotime($license['date_purchase']))}}</div>
                    <div class="col-md-1">
                        @php
                            $activated = ($license['date_activate'] !== '0000-00-00 00:00:00') && ($license['date_activate'] !== null);
                            if (!$activated)
                                echo '-';
                            else
                                echo date('Y-m-d', strtotime($license['date_activate']));
                        @endphp</div>
                    <div class="col-md-1">

                        @php

                        $expiryText = '-';
                        if ($activated) {

                            $expiryDate = null;
                            $expiryDays = null;
                            switch($license['type']) {

                                case env('LICENSE_TYPE_BASE') :
                                    $expiryText = trans('admin.ACTIVATION_LICENSE_EXPIRE_NEVER_SHORT');
                                    break;

                                case env('SUBSCRIPTION_BASE') :
                                    if($license['paddle_status'] == env('PADDLE_STATUS_DELETED')) {
                                        $expiryText = trans('admin.ACTIVATION_USER_LICENSES_STATUS_DELETED');
                                        //echo $license['paddle_status'].'<br>';
                                       // echo env('JAA_PADDLE_STATUS_DELETED');
                                    } else {
                                        $expiryDate = Helper::getSubscriptionExpireDate($license['date_purchase']);
                                    }

                                    break;

                                case env('SUPPORTED_BASE') :
                                    $expiryDays = $license['support_days'];
                                    $expiryDate = Helper::getExpirationDate($license['date_purchase'], $expiryDays);
                                    break;

                                case env('TEMP_BASE') :
                                    $expiryDays = $license['license_days'];
                                    $expiryDate = Helper::getExpirationDate($license['date_activate'], $expiryDays);
                                    break;

                                default:
                                    break;
                            }

                            if(!empty($expiryDate)) {
                                if(!is_null($expiryDays) && ($expiryDays == 0)) {
                                    $expiryText = trans('admin.COM_JAPPACTIVATION_LICENSE_EXPIRE_NEVER_SHORT');
                                } else {
                                    $expiryText = date('Y-m-d', strtotime($expiryDate));
                                }
                            }
                        }

                        echo($expiryText);
                        @endphp

                    </div>
                    <div class="col-md-1">
                        @php
                        $usageColor = 'orange';
                        if ($license['count_seats'] >= $license['seats'])
                            $usageColor = 'green';
                        elseif ($license['count_seats'] == 0)
                            $usageColor = 'red';
                        @endphp
                        <b style="color: @php echo($usageColor); @endphp">{{$license['count_seats']}} / {{$license['seats']}} </b>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{$licenses->links()}}
@stop
