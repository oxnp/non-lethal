@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

    <div class="pads">
        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#gencodes">
            Generate Pre-Activation Codes
        </button>
    </div>

    <div id="gencodes" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{route('generatePreActivationCodes')}}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input placeholder="Cid" class="form-control" type="text" name="cid[]"/>
                        </div>
                        <div class="form-group">
                            <input placeholder="Cid" class="form-control" type="text" name="cid[]"/>
                        </div>
                        <div class="form-group">
                            <input placeholder="License type" class="form-control" type="text" name="license_type" value="1"/>
                        </div>
                        <div class="form-group">
                            <input placeholder="reference" class="form-control" type="text" name="reference" value=""/>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Generate">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="list">
        <div class="list_head">
            <div class="col-md-1">ID</div>
            <div class="col-md-1">Publish</div>
            <div class="col-md-2">Name</div>
            <div class="col-md-1">Access Level</div>
            <div class="col-md-2">Upgreadable from</div>
            <div class="col-md-2">Product code</div>
            <div class="col-md-2">Paddle PID/UPID</div>
            <div class="col-md-1">Major Ver.</div>
        </div>
        <div class="list_body">
            @foreach($products as $product)
                <div class="item">
                    <div class="col-md-1 idcol">{{$product['id']}}</div>
                    <div class="col-md-1">{{$product['published']}}</div>
                    <div class="col-md-2"><a href="{{route('products.show', $product['id'])}}">{{$product['name']}}</a>
                    </div>
                    <div class="col-md-1">{{$product['access']}}</div>
                    <div class="col-md-2">@foreach($product['upgradeable_products'] as $upgradeable) {{$upgradeable}}
                        <br> @endforeach</div>
                    <div class="col-md-2">{{$product['code']}}</div>
                    <div class="col-md-2">{{$product['paddle_pid']}} / {{$product['paddle_upgrade_pid']}}</div>
                    <div class="col-md-1">{{$product['default_majver']}}.x</div>
                </div>
            @endforeach
        </div>
    </div>
    {{$products_links->links()}}
@stop
