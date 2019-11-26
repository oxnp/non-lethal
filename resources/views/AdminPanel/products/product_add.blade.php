@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

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
            <form action="{{route('products.store')}}" method="POST">
                {{csrf_field()}}
                <div class="tab-pane fade active in" id="t1" role="t1" aria-labelledby="t1">
                    <div class="col-lg-3 form-group">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input class="form-control" type="text" name="name" value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Product code</label>
                            <input class="form-control" type="text" name="code" value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type</label>
                            <select class="form-control" name="type">
                                <option value="31">Perpetual</option>
                                <option value="40">Subscription</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">License system</label>
                            <select class="form-control" name="licsystem">
                                <option value="1">NLA Licensing</option>
                                <option value="2">PACE iLok</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="form-control" name="published">
                                <option value="1">Published</option>
                                <option value="0" selected >Unpublished</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Access</label>
                            <select class="form-control" name="access">
                                @foreach($accesses as $access)
                                    <option value="{{$access['id']}}">{{$access['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Major version</label>
                            <input class="form-control" type="text" name="default_majver"  value="1"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Upgreadable products</label>
                            <select class="form-control" name="upgradeable_products[]" multiple size="20">
                                @foreach($upgradeable_products as $prod)
                                        <option value="{{$prod['id']}}">{{$prod['name']}}</option>
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
                                           @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Paddle Upgrade product</label>
                            <select class="form-control" name="paddle_upgrade_pid">
                                <option value="">--- Please choose a Paddle product ---</option>
                                @foreach($options_paddle_list as $paddle_item)
                                    <option value="{{$paddle_item['value']}}"
                                            @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-lg-offset-1 form-group">
                        <div class="form-group">
                            <label class="control-label">Listing notes</label>
                            <input class="form-control" type="text" name="notes" value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Beta release</label>
                            <select class="form-control" name="isbeta">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Debug mode</label>
                            <select class="form-control" name="debug_mode">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
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
                                   value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Upgrade prefix</label>
                            <input class="form-control" type="text" name="prefix_upgrade"
                                   value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Temp prefix</label>
                            <input class="form-control" type="text" name="prefix_temp"
                                   value=""/>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                    <div class="col-md-6">
                        @php $futures = explode(',',',,,,,,,,') @endphp
                        @php $feature_prefixes = explode(',',',,,,,,,,') @endphp
                        @foreach($futures as $future)
                            <div class="item_featured">
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
                            <input class="form-control" type="text" name="mail_from" value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Sender address</label>
                            <input class="form-control" type="text" name="mail_address" value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">BCC address</label>
                            <input class="form-control" type="text" name="mail_bcc" value=""/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mail subject</label>
                            <input class="form-control" type="text" name="mail_subject" value=""/>
                        </div>
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Mail body</label>
                            <textarea class="form-control summernote" name="mail_body" value=""></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@stop
