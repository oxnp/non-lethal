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
                    <div class="col-md-1 idcol"><input type="checkbox" value="{{$license['id']}}"
                                                       name="pre_id"/>{{$license['id']}}</div>
                    <div class="col-md-2">{{substr(chunk_split($license['serial'],5,'-'),0,-1)}}</div>
                    <div class="col-md-2">{{$license['last']}} {{$license['first']}}</div>
                    <div class="col-md-2">{{$license['name']}}</div>
                    <div class="col-md-1">{{$license['max_majver']}}.x</div>
                    <div class="col-md-1">{{date('Y-m-d', strtotime($license['date_purchase']))}}</div>
                    <div class="col-md-1">{{date('Y-m-d', strtotime($license['date_activate']))}}</div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                </div>
            @endforeach
        </div>
    </div>
    {{$licenses->links()}}
@stop
