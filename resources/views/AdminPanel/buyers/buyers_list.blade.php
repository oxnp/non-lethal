@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

    <div class="pads">
        <form name="search" method="GET" action="">
            <input value="{{$filter['search_string']}}" class="form-control" type="text" name="searchstring" placeholder="Search..."/>
            <input type="submit" class="btn btn-primary" value="Search"/>
        </form>
        <form action="{{route('exportBuyers')}}" method="POST" name="export_codes">
            {{csrf_field()}}
            <input type="submit" class="btn btn-primary btn-md" value="Export Buyers" />
        </form>
        <a class="btn btn-primary btn-md" href="{{route('buyers.create')}}">
            Add buyer
        </a>
        <a class="btn btn-primary btn-md" id="add_license">
            Add license
        </a>
    </div>
    <div class="list">
        <div class="list_head">
            <div class="col-md-1">ID</div>
            <div class="col-md-2">Name</div>
            <div class="col-md-2">Company name</div>
            <div class="col-md-3">Email address</div>
            <div class="col-md-2">Licenses</div>
            <div class="col-md-2">Seats</div>
        </div>
        <div class="list_body">
            @foreach($buyers as $buyer)
                <div class="item">
                    <div class="col-md-1 idcol"><input type="checkbox" value="{{$buyer->id}}" name="precode_id" /> {{$buyer->id}}</div>
                    <div class="col-md-2"><a
                            href="{{route('buyers.show',$buyer->id)}}">{{$buyer->first}} {{$buyer->last}}</a></div>
                    <div class="col-md-2">{{$buyer->company}}</div>
                    <div class="col-md-3">{{$buyer->email}}</div>
                    <div class="col-md-2">{{$buyer->licensecount}}</div>
                    <div class="col-md-2">{{$buyer->seatcount}}</div>
                </div>
            @endforeach
        </div>
    </div>
    {{$buyers->links()}}
@stop
