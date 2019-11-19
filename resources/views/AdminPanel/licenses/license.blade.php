@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

    <div class="row formgroup">
        <form action="{{route('licenses.update',$license[0]['id'])}}" method="POST">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="PUT">
            <div class="col-lg-4 form-group">
                <input type="hidden" value="{{$license[0]['id']}}" name="id"/>
                <div class="form-group">
                    <label class="control-label">License owner</label>
                    <input class="form-control" disabled="disabled" type="text"
                           value="{{$license[0]['last']}} {{$license[0]['first']}}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Product</label>
                    <select class="form-control" name="product_id">
                        @foreach($products as $product)
                            <option @if($product['type']!=$license[0]['type'])
                                    disabled="disabled"
                                    @endif
                                    @if($license[0]['product_id'] == $product['id'])
                                    selected="selected"
                                    @endif
                                    value="{{$product['id']}}">{{$product['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Purchase date</label>
                    <input class="form-control" type="date"
                           value="{{date('Y-m-d',strtotime($license[0]['date_purchase']))}}"/>
                </div>
                @if(!empty($license[0]['serial']))
                    <div class="form-group">
                        <label class="control-label">Serial number</label>
                        <input class="form-control" name="serial" type="text"
                               value="{{substr(chunk_split($license[0]['serial'],5,'-'),0,-1)}}"/>
                    </div>
                @endif
                @if(!empty($license[0]['ilok_code']))
                    <div class="form-group">
                        <label class="control-label">iLok code</label>
                        <input class="form-control" name="ilok_code" type="text" value="{{$license[0]['ilok_code']}}"/>
                    </div>
                @endif
                <div class="form-group">
                    <label class="control-label">First activation</label>
                    <input class="form-control" disabled="disabled" type="text" name="date_activate"
                           value="{{$license[0]['date_activate']}}"/>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="form-group">
                    <label class="control-label">Supported major version</label>
                    <input class="form-control" type="text" name="max_majver" value="{{$license[0]['max_majver']}}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Number of seats</label>
                    <input class="form-control" type="text" name="seats" value="{{$license[0]['seats']}}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">License type</label>
                    <select class="form-control" name="type" @if($license[0]['type']==40) disabled="disabled" @endif>
                        <option @if($license[0]['type']==31) selected="selected" @endif value="31">Permanent license
                        </option>
                        <option @if($license[0]['type']==40) selected="selected" @else disabled="disabled"
                                @endif value="40">Subscription
                        </option>
                        <option @if($license[0]['type']==82) selected="selected" @endif value="82">Supported license
                        </option>
                        <option @if($license[0]['type']==91) selected="selected" @endif value="91">Temporary license
                        </option>
                    </select>
                </div>
                <div class="form-group soft_hide" data-type="82">
                    <label class="control-label">Supported for</label>
                    <input class="form-control" type="number" min="1" step="1" name="support_days"
                           value="{{$license[0]['support_days']}}"/>
                </div>
                <div class="form-group soft_hide" data-type="91">
                    <label class="control-label">License valid for</label>
                    <input class="form-control" type="number" min="1" step="1" name="license_days"
                           value="{{$license[0]['license_days']}}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Feature options</label>
                    <div>
                        <input id="prod_features" type="checkbox" name="prod_features"
                               @if($license[0]['prod_features']==1) checked="checked" @endif value="0"/>
                        <label for="prod_features">Pro</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Paddle checkout order id</label>
                    <input class="form-control" type="text" disabled="disabled" name="paddle_oid"
                           value="{{$license[0]['paddle_oid']}}"/>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="control-label">Additional notes</label>
                    <textarea rows="15" class="form-control summernote" name="notes"
                              value="{{$license[0]['notes']}}">{{$license[0]['notes']}}</textarea>
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
                            @foreach($seats as $seat)
                            <div class="item">
                                <div class="col-md-3">{{$seat->mac}}</div>
                                <div class="col-md-3">{{$seat->system_id}}</div>
                                <div class="col-md-3">{{$seat->activation_date}}</div>
                                <div class="col-md-3">
                                    <input type="checkbox" name="remove[]" value="{{$seat->id}}"/>
                                </div>
                            </div>
                            @endforeach
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
