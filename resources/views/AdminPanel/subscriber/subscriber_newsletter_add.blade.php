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
        <form action="{{route('newsletters.store')}}" method="POST">
            {{csrf_field()}}
            <div class="col-md-8">
                <div class="col-lg-6 form-group">
                    <div class="form-group">
                        <label class="control-label">Subject</label>
                        <input class="form-control" type="text" value="" name="subject"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Alias</label>
                        <input class="form-control" type="text" value="" name="alias"/>
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <div class="form-group">
                        <label class="control-label">Published</label>
                        <select class="form-control" name="published">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Visible</label>
                        <select class="form-control" name="visible">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Send HTML Version</label>
                        <select class="form-control" name="send_html_version">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Summary</label>
                        <textarea name="summary" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
            <textarea rows="15" class="form-control summernote" name="body_html"
                      value=""></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Lists</label>
                    @foreach($subscriber_groups as $group)
                        <div>
                            <input id="group{{$group['id']}}" value="{{$group['id']}}" type="checkbox" name="subscription_group_ids[]">
                            <label for="group{{$group['id']}}">{{$group['group_name']}}</label>
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label class="control-label">From name</label>
                    <input class="form-control" type="text" value="" name="from_name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">From address</label>
                    <input class="form-control" type="text" value="" name="from_adress"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Reply-to Name</label>
                    <input class="form-control" type="text" value="" name="reply_to_name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Reply-to address</label>
                    <input class="form-control" type="text" value="" name="reply_to_adress"/>
                </div>
            </div>
            <div class="col-md-12">
                <input class="btn btn-primary" type="submit">
            </div>
        </form>
    </div>
@stop
