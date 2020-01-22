@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')

@section('content')
    <h1>Subscribers list</h1>
    <div class="topmenu row">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('subscribers.index')}}">
                        Subscribers
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
            <div class="col-md-4">Name</div>
            <div class="col-md-4">Email</div>
            <div class="col-md-4">Subscription</div>
        </div>
        <div class="list_body">
            @foreach($data_subscriber['subscribers'] as $subscriber)
                <div class="item">
                    <div class="col-md-4"><a href="{{route('subscribers.show',$subscriber['id'])}}">{{$subscriber['name']}}</a></div>
                    <div class="col-md-4">{{$subscriber['email']}}</div>
                    <div class="col-md-4">{{implode(',',$subscriber['group_name'])}}</div>
                </div>
            @endforeach
        </div>
    </div>

{{$data_subscriber['paginate']->links()}}
@stop
