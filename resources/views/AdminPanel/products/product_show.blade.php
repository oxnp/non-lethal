@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
<form action="{{route('products.update',$product['id'])}}" method="POST">
    {{csrf_field()}}
    <input name="_method" type="hidden" value="PUT">

    Name
    <input type="text" name="name" value="{{$product['name']}}" /><br>

    Product Code
    <input type="text" name="code" value="{{$product['code']}}" /><br>

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
            <option value="{{$access['id']}}" @if($product['access'] == $access['id']) selected @endif>{{$access['name']}}</option>
    @endforeach
    </select> <br>
    Major version
    <input type="text" name="name" value="{{$product['default_majver']}}" /><br>

    Upgradable product
    @php $upgreadeable_products_id = json_decode($product['upgradeable_products'])@endphp
    <select name="upgradeable_products[]" multiple size="20">
    @foreach($upgradeable_products as $prod)
        @if(!empty($upgreadeable_products_id))
            <option value="{{$prod['id']}}" @if(in_array($prod['id'],$upgreadeable_products_id)) selected @endif>{{$prod['name']}}</option>
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
            <option value="{{$paddle_item['value']}}" @if($product['paddle_pid'] == $paddle_item['value']) selected @endif @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
        @endforeach
    </select>
    <br>

    Paddle Upgrade Product
    <select name="paddle_upgrade_pid">
        <option value="">--- Please choose a Paddle product ---</option>
        @foreach($options_paddle_list as $paddle_item)
            <option value="{{$paddle_item['value']}}" @if($product['paddle_upgrade_pid'] == $paddle_item['value']) selected @endif @if($paddle_item['disable'] == true) disabled @endif>{{$paddle_item['text']}}</option>
        @endforeach
    </select>
    <br>

    Listing notes
    <input type="text" name="notes" value="{{$product['notes']}}" /><br>

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
    <input type="text" name="prefix_full" value="{{$product['prefix_full']}}" /><br>

    Upgrade prefix
    <input type="text" name="prefix_upgrade" value="{{$product['prefix_upgrade']}}" /><br>

    Temp prefix
    <input type="text" name="prefix_temp" value="{{$product['prefix_temp']}}" /><br>




    <br>
    ------------3 TAB ------------<br>
    Sender name
    <input type="text" name="mail_from" value="{{$product['mail_from']}}" /><br>

    Sender address
    <input type="text" name="mail_address" value="{{$product['mail_address']}}" /><br>

    BCC address
    <input type="text" name="mail_bcc" value="{{$product['mail_bcc']}}" /><br>

    Mail subject
    <input type="text" name="mail_subject" value="{{$product['mail_subject']}}" /><br>

    Mail body
    <input type="text" name="mail_body" value="{{$product['mail_body']}}" /><br>
<!--
    @foreach($product as $key => $value)
        {{$key}}
     <input type="text" value="{{$product[$key]}}" name="{{$key}}"/><br>
    @endforeach
-->
    <input type="submit">
</form>

<b>Feature Pre-Activation code settings</b><br>
@php $futures = explode(',',$product['features']) @endphp
@php $feature_prefixes = explode(',',$product['feature_prefixes']) @endphp

@foreach($futures as $future)
    <form action="{{route('generateFeaturePreCodeAJAX')}}" method="POST" name="precodegenerate-{{$loop->index}}">
        {{csrf_field()}}

        <input type="hidden" name="productid" value="{{$product['id']}}" />
        <input type="text" name="featurename" value="{{$future}}" />
        <input type="text" name="prefix" value="{{$feature_prefixes[$loop->index]}}" />
        <input autocomplete="off" name="amount" type="number" step="1" min="1" value="50"/>
        <input type="submit" value="Generate Batch" class="btn btn-primary" /><br>
    </form>
@endforeach


@stop
