@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <h1>Product</h1>
    <ul class="nav nav-tabs" id="toptab" role="tablist">
        <li class="nav-item active">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#t1" role="tab" aria-controls="t1"
               aria-selected="true">General</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#t2" role="tab" aria-controls="t2"
               aria-selected="false">Pre-Activation</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#t3" role="tab" aria-controls="t3"
               aria-selected="false">Serial mailer settings</a>
        </li>
    </ul>

    <div class="row formgroup">
        <div class="tab-content" id="myTabContent">
            <form action="{{route('products.update',$product['id'])}}" method="POST">
                {{csrf_field()}}
                <input name="_method" type="hidden" value="PUT">
                <div class="tab-pane fade active in" id="t1" role="t1" aria-labelledby="t1">
                    <div class="col-lg-3 form-group">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input class="form-control" type="text" name="name" value="{{$product['name']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Product code</label>
                            <input class="form-control" type="text" name="code" value="{{$product['code']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type</label>
                            <select class="form-control" name="type">
                                <option value="31" @if($product['type'] == 31) selected @endif>Perpetual</option>
                                <option value="40" @if($product['type'] == 40) selected @endif>Subscription</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">License system</label>
                            <select class="form-control" name="licsystem">
                                <option value="1" @if($product['licsystem'] == 1) selected @endif>NLA Licensing</option>
                                <option value="2" @if($product['licsystem'] == 2) selected @endif>PACE iLok</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="form-control" name="published">
                                <option value="1" @if($product['published'] == 0) selected @endif>Published</option>
                                <option value="0" @if($product['published'] == 1) selected @endif>Unpublished</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Access</label>
                            <select class="form-control" name="access">
                                @foreach($accesses as $access)
                                    <option value="{{$access['id']}}"
                                            @if($product['access'] == $access['id']) selected @endif>{{$access['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Major version</label>
                            <input class="form-control" type="text" name="default_majver"
                                   value="{{$product['default_majver']}}"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Upgrade Link Page</label>
                            <input class="form-control" type="text" name="upgrade_link_page"  value="{{$product['upgrade_link_page']}}"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Upgreadable products</label>
                            @php $upgreadeable_products_id = json_decode($product['upgradeable_products'])@endphp
                            <select class="form-control" name="upgradeable_products[]" multiple size="20">
                                @foreach($upgradeable_products as $prod)
                                    @if(!empty($upgreadeable_products_id))
                                        <option value="{{$prod['id']}}"
                                                @if(in_array($prod['id'],$upgreadeable_products_id)) selected @endif>{{$prod['name']}}</option>
                                    @else
                                        <option value="{{$prod['id']}}">{{$prod['name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-lg-offset-1 form-group">
                        <div class="form-group">
                            <label class="control-label">Paddle product</label>
                            <select class="form-control" name="paddle_pid">
                                <option value="">--- Please choose a Paddle product ---</option>
                                @foreach($options_paddle_list as $paddle_item)
                                    <option value="{{$paddle_item['value']}}"
                                            @if($product['paddle_pid'] == $paddle_item['value']) selected
                                            @endif @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Paddle Upgrade product</label>
                            <select class="form-control" name="paddle_upgrade_pid">
                                <option value="">--- Please choose a Paddle product ---</option>
                                @foreach($options_paddle_list as $paddle_item)
                                    <option value="{{$paddle_item['value']}}"
                                            @if($product['paddle_upgrade_pid'] == $paddle_item['value']) selected
                                            @endif @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-lg-offset-1 form-group">
                        <div class="form-group">
                            <label class="control-label">Listing notes</label>
                            <input class="form-control" type="text" name="notes" value="{{$product['notes']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Beta release</label>
                            <select class="form-control" name="isbeta">
                                <option value="1" @if($product['isbeta'] == 1) selected @endif>Yes</option>
                                <option value="0" @if($product['isbeta'] == 0) selected @endif>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Debug mode</label>
                            <select class="form-control" name="debug_mode">
                                <option value="1" @if($product['debug_mode'] == 1) selected @endif>Yes</option>
                                <option value="0" @if($product['debug_mode'] == 0) selected @endif>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                </div>
                <div class="tab-pane fade" id="t2" role="t2" aria-labelledby="t2">
                    <div class="col-lg-3 form-group">
                        <div class="form-group">
                            <label class="control-label">Full prefix</label>
                            <input class="form-control" type="text" name="prefix_full"
                                   value="{{$product['prefix_full']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Upgrade prefix</label>
                            <input class="form-control" type="text" name="prefix_upgrade"
                                   value="{{$product['prefix_upgrade']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Temp prefix</label>
                            <input class="form-control" type="text" name="prefix_temp"
                                   value="{{$product['prefix_temp']}}"/>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                    <div class="col-md-6">
                        @php $futures = explode(',',$product['features']) @endphp
                        @php $feature_prefixes = explode(',',$product['feature_prefixes']) @endphp
                        @foreach($futures as $future)
                            <div class="item_featured">
                                <input type="hidden" name="productid" value="{{$product['id']}}"/>
                                <input class="form-control" placeholder="Feature" type="text" name="features[]"
                                       value="{{$future}}"/>
                                <input maxlength="5" class="form-control" placeholder="Prefix" type="text"
                                       name="feature_prefixes[]"
                                       value="{{$feature_prefixes[$loop->index]}}"/>
                                <input class="form-control" placeholder="Amount" autocomplete="off" name="amount[]"
                                       type="number" step="1" min="1" value="50"/>
                                @if(!empty($future) && !empty($feature_prefixes[$loop->index]) && strlen($feature_prefixes[$loop->index])==5)
                                    <button class="btn btn-primary gencodes">Generate codes</button>
                                @else
                                    <button disabled="disabled" class="btn btn-primary">Fill all fields first</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="t3" role="t3" aria-labelledby="t3">
                    <div class="col-md-6 form-group">
                        <div class="form-group">
                            <label class="control-label">Sender name</label>
                            <input class="form-control" type="text" name="mail_from" value="{{$product['mail_from']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Sender address</label>
                            <input class="form-control" type="text" name="mail_address"
                                   value="{{$product['mail_address']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">BCC address</label>
                            <input class="form-control" type="text" name="mail_bcc" value="{{$product['mail_bcc']}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mail subject</label>
                            <input class="form-control" type="text" name="mail_subject"
                                   value="{{$product['mail_subject']}}"/>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Mail body</label>
                            <textarea class="form-control summernote" name="mail_body"
                                      value="{{$product['mail_body']}}">{{$product['mail_body']}}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function () {
            $('button.gencodes').click(function (e) {
                e.preventDefault();
                let but = $(this);
                let features = $(this).closest('.item_featured').find('input[name^="features"]').val();
                let feature_prefixes = $(this).closest('.item_featured').find('input[name^="feature_prefixes"]').val();
                let amount = $(this).closest('.item_featured').find('input[name^="amount"]').val();
                $.ajax({
                    url: "{{route('generateFeaturePreCodeAJAX')}}",
                    type: "POST",
                    data: "features=" + features + "&prefix=" + feature_prefixes + "&amount=" + amount + "&productid={{$product['id']}}&_token={{csrf_token()}}",
                    success: function (data) {
                        console.log(data);
                        $(but).text('Success!');
                        $(but).addClass('btn-success');
                    }
                })
            })
        })
    </script>


    <!--<form action="{{route('products.update',$product['id'])}}" method="POST">
        {{csrf_field()}}
        <input name="_method" type="hidden" value="PUT">

        Name
        <input type="text" name="name" value="{{$product['name']}}"/><br>

        Product Code
        <input type="text" name="code" value="{{$product['code']}}"/><br>

        Type
        <select name="type">
            <option value="31" @if($product['type'] == 31) selected @endif>Perpetual</option>
            <option value="40" @if($product['type'] == 40) selected @endif>Subscription</option>
        </select>
        <br>
        Licenses system
        <select name="licsystem">
            <option value="1" @if($product['licsystem'] == 1) selected @endif>NLA Licensing</option>
            <option value="2" @if($product['licsystem'] == 2) selected @endif>PACE iLok</option>
        </select>
        <br>
        Status
        <select name="published">
            <option value="1" @if($product['published'] == 0) selected @endif>Published</option>
            <option value="0" @if($product['published'] == 1) selected @endif>Unpublished</option>
        </select>
        <br>
        Access
        <select name="access">
            @foreach($accesses as $access)
        <option value="{{$access['id']}}"
                        @if($product['access'] == $access['id']) selected @endif>{{$access['name']}}</option>
            @endforeach
        </select> <br>
        Major version
        <input type="text" name="name" value="{{$product['default_majver']}}"/><br>

        Upgradable product
        @php $upgreadeable_products_id = json_decode($product['upgradeable_products'])@endphp
        <select name="upgradeable_products[]" multiple size="20">
            @foreach($upgradeable_products as $prod)
        @if(!empty($upgreadeable_products_id))
            <option value="{{$prod['id']}}"
                            @if(in_array($prod['id'],$upgreadeable_products_id)) selected @endif>{{$prod['name']}}</option>
                @else
            <option value="{{$prod['id']}}">{{$prod['name']}}</option>
                @endif
    @endforeach
        </select>
        <br>
        Paddle Product
        <select name="paddle_pid">
            <option value="">--- Please choose a Paddle product ---</option>
            @foreach($options_paddle_list as $paddle_item)
        <option value="{{$paddle_item['value']}}" @if($product['paddle_pid'] == $paddle_item['value']) selected
                        @endif @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
            @endforeach
        </select>
        <br>

        Paddle Upgrade Product
        <select name="paddle_upgrade_pid">
            <option value="">--- Please choose a Paddle product ---</option>
            @foreach($options_paddle_list as $paddle_item)
        <option value="{{$paddle_item['value']}}"
                        @if($product['paddle_upgrade_pid'] == $paddle_item['value']) selected
                        @endif @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
            @endforeach
        </select>
        <br>

        Listing notes
        <input type="text" name="notes" value="{{$product['notes']}}"/><br>

        Beta release
        <select name="beta">
            <option value="1" @if($product['isbeta'] == 1) selected @endif>Yes</option>
            <option value="0" @if($product['isbeta'] == 0) selected @endif>No</option>
        </select>
        <br>
        Debug mode
        <select name="debug_mode">
            <option value="1" @if($product['debug_mode'] == 1) selected @endif>Yes</option>
            <option value="0" @if($product['debug_mode'] == 0) selected @endif>No</option>
        </select>
        <br>
        ------------2 TAB ------------<br>
        Full prefix
        <input type="text" name="prefix_full" value="{{$product['prefix_full']}}"/><br>

        Upgrade prefix
        <input type="text" name="prefix_upgrade" value="{{$product['prefix_upgrade']}}"/><br>

        Temp prefix
        <input type="text" name="prefix_temp" value="{{$product['prefix_temp']}}"/><br>


        <br>
        ------------3 TAB ------------<br>
        Sender name
        <input type="text" name="mail_from" value="{{$product['mail_from']}}"/><br>

        Sender address
        <input type="text" name="mail_address" value="{{$product['mail_address']}}"/><br>

        BCC address
        <input type="text" name="mail_bcc" value="{{$product['mail_bcc']}}"/><br>

        Mail subject
        <input type="text" name="mail_subject" value="{{$product['mail_subject']}}"/><br>

        Mail body
        <input type="text" name="mail_body" value="{{$product['mail_body']}}"/><br>

        <input type="submit">
    </form>

    <b>Feature Pre-Activation code settings</b><br>
    @php $futures = explode(',',$product['features']) @endphp
    @php $feature_prefixes = explode(',',$product['feature_prefixes']) @endphp

    @foreach($futures as $future)
        <form action="{{route('generateFeaturePreCodeAJAX')}}" method="POST" name="precodegenerate-{{$loop->index}}">
            {{csrf_field()}}

            <input type="hidden" name="productid" value="{{$product['id']}}"/>
            <input type="text" name="featurename" value="{{$future}}"/>
            <input type="text" name="prefix" value="{{$feature_prefixes[$loop->index]}}"/>
            <input autocomplete="off" name="amount" type="number" step="1" min="1" value="50"/>
            <input type="submit" value="Generate Batch" class="btn btn-primary"/><br>
        </form>
    @endforeach
        -->

@stop
