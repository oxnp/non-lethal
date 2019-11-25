@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

    <div class="row formgroup">
        <form action="{{route('licenses.store',['buyer_id'=>$buyer['id']])}}" method="POST">
            {{csrf_field()}}
            <div class="col-lg-4 form-group">
                <div class="form-group">
                    <label class="control-label">License owner</label>
                    <input class="form-control" disabled="disabled" type="text"
                           value="{{$buyer['last']}} {{$buyer['first']}}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Product</label>
                    <select class="form-control" name="product_id">
                        @foreach($products as $product)
                            @if ($product['type'] != 40)
                                <option value="{{$product['id']}}">{{$product['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Purchase date</label>
                    <input class="form-control" name="date_purchase" type="date" value="{{date('Y-m-d')}}"/>

                    <label class="control-label">Serial number</label>
                        <input
                            class="form-control" name="serial" type="text" value="" placeholder="Leave empty for auto generation"/>
                    </div>

                <div class="form-group">
                    <label class="control-label">First activation</label>
                    <input class="form-control" disabled="disabled" type="text" name="date_activate" value="License not activated yet!"/>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="form-group">
                    <label class="control-label">Supported major version</label>
                    <input class="form-control" type="text" name="max_majver" value="3"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Number of seats</label>
                    <input class="form-control" type="text" name="seats" value="1"/>
                </div>
                <div class="form-group">
                    <label class="control-label">License type</label>
                    <select class="form-control" name="license_type"/>
                        <option value="31">Permanent license</option>
                        <option disabled="disabled" value="40">Subscription</option>
                        <option value="82">Supported license</option>
                        <option value="91">Temporary license</option>
                    </select>
                </div>
                <div class="form-group soft_hide" data-type="82">
                    <label class="control-label">Supported for</label>
                    <input class="form-control" type="number" min="1" step="1" name="support_days"
                           value="365"/>
                </div>
                <div class="form-group soft_hide" data-type="91">
                    <label class="control-label">License valid for</label>
                    <input class="form-control" type="number" min="1" step="1" name="license_days"
                           value="365"/>
                </div>
                Cannot calculate expiry date: The license has not been saved yet...
                <div class="form-group">
                    <label class="control-label">Feature options</label>
                    Please save this license first to apply the supported features
                </div>
                <div class="form-group">
                    <label class="control-label">Paddle checkout order id</label>
                    <input class="form-control" type="text" disabled="disabled" name="paddle_oid"
                           value=""/>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="control-label">Additional notes</label>
                    <textarea rows="15" class="form-control summernote" name="notes"
                              value=""></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Licensed machines (seats)</label>
                    <div class="list">
                        <div class="list_head">
                            <div class="col-md-3">MAC</div>
                            <div class="col-md-3">System ID (md5)</div>
                            <div class="col-md-3">Activation date</div>
                            <div class="col-md-3">Remove</div>
                        </div>
                        <div class="list_body">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <input class="btn btn-primary" type="submit">
            </div>
        </form>
    </div>
@stop
