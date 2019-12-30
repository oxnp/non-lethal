@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')

@section('content')
<div class="topmenu row">
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{route('subscribers.index')}}">
                    Subscribers
                </a>
            </li>
            <li class="nav-item d-inline">
                <a class="nav-link" href="#">
                    Newsletters
                </a>
            </li>
        </ul>
    </div>
</div>
@stop
