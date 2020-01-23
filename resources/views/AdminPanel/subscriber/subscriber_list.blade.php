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
            <div class="col-md-2">Name</div>
            <div class="col-md-4">Email</div>
            <div class="col-md-4">Subscription</div>
            <div class="col-md-2">Actions</div>
        </div>
        <div class="list_body">
            @foreach($data_subscriber['subscribers'] as $subscriber)
                <div class="item">
                    <div class="col-md-2"><a href="{{route('subscribers.show',$subscriber['id'])}}">{{$subscriber['name']}}</a></div>
                    <div class="col-md-4">{{$subscriber['email']}}</div>
                    <div class="col-md-4">{{implode(',',$subscriber['group_name'])}}</div>
                    <div class="col-md-2">
                        <a style="margin-right: 15px" class="btn btn-primary" href="{{route('subscribers.show',$subscriber['id'])}}"><i class="fa fa-edit"></i></a>
                    <form style="float:right" method="POST" action="{{route('subscribers.destroy',$subscriber['id'])}}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i></button>
                    </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

{{$data_subscriber['paginate']->links()}}
@stop
