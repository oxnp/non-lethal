@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
<form action="{{route('buyers.update',$buyer['id'])}}" method="POST">
    {{csrf_field()}}
    <input name="_method" type="hidden" value="PUT">
    @foreach($buyer as $key => $value)
        {{$key}}
     <input type="text" value="{{$buyer[$key]}}" name="{{$key}}"/><br>
    @endforeach
    <input type="submit">
</form>
@stop
