@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <table width="900px">
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Company name</td>
            <td>Email address</td>
            <td>Linceses</td>
            <td>Seats</td>
        </tr>
  @foreach($buyers as $buyer)
            <tr>
                <td>{{$buyer->id}}</td>
                <td><a href="{{route('buyers.show',$buyer->id)}}">{{$buyer->first}} {{$buyer->last}}</a></td>
                <td>{{$buyer->company}}</td>
                <td>{{$buyer->email}}</td>
                <td>{{$buyer->licensecount}}</td>
                <td>{{$buyer->seatcount}}</td>
            </tr>
  @endforeach
    </table>
    {{$buyers->links()}}
@stop
