@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Add Subscriber</h1>
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
    <div class="row formgroup">
        <form action="{{route('subscribers.store')}}" method="POST">
            {{csrf_field()}}
            <div class="col-lg-6 form-group">
                <div class="form-group">
                    <label class="control-label">Name</label>
                    <input class="form-control" type="text" value="" name="name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input class="form-control" type="text" value="" name="email"/>
                </div>
                <div class="hide">
                    <div class="form-group">
                        <label class="control-label">User id</label>
                        <input class="form-control" disabled="disabled" type="text" value="" name="user_id"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Created at</label>
                        <input class="form-control" disabled="disabled" type="text" value="" name="created_at"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 form-group">
                <div class="hide">
                    <div class="form-group">
                        <label class="control-label">Enabled</label>
                        <select class="form-control" name="enabled">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Confirmed</label>
                        <select class="form-control" name="confirmed">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Receive html</label>
                        <select class="form-control" name="receive_html">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Subscription groups</label>
                    @foreach($subscriber_groups as $group)
                        <div>
                            <input
                                id="group{{$group['id']}}" value="{{$group['id']}}" type="checkbox" name="subscription_group_ids[]">
                            <label for="group{{$group['id']}}">{{$group['group_name']}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12">
                <input class="btn btn-primary" value="Save" type="submit">
            </div>
        </form>
    </div>
@stop
