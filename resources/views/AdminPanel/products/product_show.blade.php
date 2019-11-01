@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
<form action="{{route('products.update',$product['id'])}}" method="POST">
    {{csrf_field()}}
    <input name="_method" type="hidden" value="PUT">

    Name
    <input type="text" name="name" value="{{$product['name']}}" /><br>
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

    @foreach($product as $key => $value)
        {{$key}}
     <input type="text" value="{{$product[$key]}}" name="{{$key}}"/><br>
    @endforeach
    <input type="submit">
</form>
@stop
