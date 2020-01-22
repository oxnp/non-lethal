@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')

@section('content')
    <h1>Subscriber</h1>
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
    <div class="row formgroup">
        <form action="{{route('subscribers.update',$subscriber->id)}}" method="POST">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="PUT">
            <div class="col-lg-6 form-group">
                <div class="form-group">
                    <label class="control-label">Name</label>
                    <input class="form-control" type="text" value="{{$subscriber->name}}" name="name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input class="form-control" type="text" value="{{$subscriber->email}}" name="email"/>
                </div>
                <div class="form-group">
                    <label class="control-label">User id</label>
                    <input class="form-control" disabled="disabled" type="text" value="{{$subscriber->user_id}}" name="user_id"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Created at</label>
                    <input class="form-control" disabled="disabled" type="text" value="{{$subscriber->created_at}}" name="created_at"/>
                </div>
            </div>
            <div class="col-lg-6 form-group">
                <div class="form-group">
                    <label class="control-label">Enabled</label>
                    <select class="form-control" name="enabled">
                        <option @if($subscriber->enabled == 0) selected @endif value="0">No</option>
                        <option @if($subscriber->enabled == 1) selected @endif value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Confirmed</label>
                    <select class="form-control" name="confirmed">
                        <option @if($subscriber->confirmed == 0) selected @endif value="0">No</option>
                        <option @if($subscriber->confirmed == 1) selected @endif value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Receive html</label>
                    <select class="form-control" name="receive_html">
                        <option @if($subscriber->receive_html == 0) selected @endif value="0">No</option>
                        <option @if($subscriber->receive_html == 1) selected @endif value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Subscription groups</label>
                    @php
                        $ids = explode(',',$subscriber->subscription_group_ids);
                    @endphp
                    @foreach($subscriber_groups as $group)
                        <div>
                            <input
                                @if(in_array($group['id'],$ids))
                                    checked="checked"
                                @endif
                                id="group{{$group['id']}}" value="{{$group['id']}}" type="checkbox" name="subscription_group_ids[]">
                            <label for="group{{$group['id']}}">{{$group['group_name']}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12">
                <input class="btn btn-primary" type="submit">
            </div>
        </form>
    </div>
@stop
