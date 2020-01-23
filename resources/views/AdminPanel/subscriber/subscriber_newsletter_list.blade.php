@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')

@section('content')
    <h1>Newsletter list</h1>
<div class="topmenu row">
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{route('subscribers.index')}}">
                    Subscribers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('subscribers.create')}}">
                    Add Subscriber
                </a>
            </li>
            <li class="nav-item d-inline">
                <a class="nav-link" href="{{route('newsletters.index')}}">
                    Newsletters
                </a>
            </li>
            <li class="nav-item d-inline">
                <a class="nav-link" href="{{route('newsletters.create')}}">
                    Add newsletter
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="list">
    <div class="list_head">
        <div class="col-md-1">ID</div>
        <div class="col-md-4">Subject</div>
        <div class="col-md-3">Send date</div>
        <div class="col-md-2">Sender</div>
        <div class="col-md-1">Visible</div>
        <div class="col-md-1">Publish</div>
    </div>
    <div class="list_body">
        @foreach($newsletters as $newsletter)
            <div class="item">
                <div class="col-md-1">{{$newsletter->id}}</div>
                <div class="col-md-4"><a href="{{route('newsletters.show',$newsletter->id)}}">{{$newsletter->subject}}</a></div>
                <div class="col-md-3">{{$newsletter->send_date}}</div>
                <div class="col-md-2">{{$newsletter->sender}}</div>
                <div class="col-md-1">@if($newsletter->visible==1)<i class="fas fa-check"></i>@else<i style="color:red" class="fas fa-window-close"></i>@endif</div>
                <div class="col-md-1">@if($newsletter->published==1)<i class="fas fa-check"></i>@else<i style="color:red" class="fas fa-window-close"></i>@endif</div>
            </div>
        @endforeach
    </div>
</div>
@stop
