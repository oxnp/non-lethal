@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('sliders.create')}}">
            Add item
        </a>
    </div>
    <div class="container-fluid">
        @foreach($slides as $slide)
            <div class="item">
                <a href="{{route('sliders.show',$slide->id)}}">{{$slide->title}}</a>
            </div>
        @endforeach
    </div>
@stop
