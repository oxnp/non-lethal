@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')

@section('content')
    <h1>Newsletter</h1>
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
        <div class="col-md-12" style="padding: 0 30px">
            <input class="btn btn-primary" form="saveform" value="Save" type="submit">
            <input class="btn btn-primary" form="nsend" value="Send" type="submit">
        </div>
        <div style="clear:both;padding:15px;overflow:hidden;">
            <form id="nsend" action="{{route('newsletterSend',$newsletter->id)}}" method="POST">
                {{csrf_field()}}
            </form>
        </div>
        <form id="saveform" action="{{route('newsletters.update',$newsletter->id)}}" method="POST">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="PUT">
            <div class="col-md-8">
                <div class="col-lg-6 form-group">
                    <div class="form-group">
                        <label class="control-label">Subject</label>
                        <input class="form-control" type="text" value="{{$newsletter->subject}}" name="subject"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Alias</label>
                        <input class="form-control" type="text" value="{{$newsletter->alias}}" name="alias"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Created date</label><br/>
                        {{date('d.m.Y',strtotime($newsletter->created_at))}}
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sent date</label><br/>
                        {{date('d.m.Y',strtotime($newsletter->send_date))}}
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sent by</label><br/>
                        {{$newsletter->sender}}
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <div class="form-group">
                        <label class="control-label">Published</label>
                        <select class="form-control" name="published">
                            <option @if($newsletter->published==1)
                                    selected="selected"
                                    @endif value="1">Yes
                            </option>
                            <option @if($newsletter->published==0)
                                    selected="selected"
                                    @endif value="0">No
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Visible</label>
                        <select class="form-control" name="visible">
                            <option @if($newsletter->visible==1)
                                    selected="selected"
                                    @endif value="1">Yes
                            </option>
                            <option @if($newsletter->visible==0)
                                    selected="selected"
                                    @endif value="0">No
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Send HTML Version</label>
                        <select class="form-control" name="send_html_version">
                            <option @if($newsletter->send_html_version==1)
                                    selected="selected"
                                    @endif value="1">Yes
                            </option>
                            <option @if($newsletter->send_html_version==0)
                                    selected="selected"
                                    @endif value="0">No
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Summary</label>
                        <textarea name="summary" class="form-control">{{$newsletter->summary}}</textarea>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
            <textarea rows="15" class="form-control summernote" name="body_html"
                      value="{{$newsletter->body_html}}">{{$newsletter->body_html}}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Lists</label>
                    @php
                        $ids = explode(',',$newsletter->subscription_group_ids);
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
                <div class="form-group">
                    <label class="control-label">From name</label>
                    <input class="form-control" type="text" value="{{$newsletter->from_name}}" name="from_name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">From address</label>
                    <input class="form-control" type="text" value="{{$newsletter->from_adress}}" name="from_adress"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Reply-to Name</label>
                    <input class="form-control" type="text" value="{{$newsletter->reply_to_name}}" name="reply_to_name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Reply-to address</label>
                    <input class="form-control" type="text" value="{{$newsletter->reply_to_adress}}" name="reply_to_adress"/>
                </div>
            </div>
        </form>

    </div>
@stop
