@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Mail template</h1>
    <form action="{{route('emails-templates.update',$template->id)}}" method="POST">
        {{csrf_field()}}
        <input name="_method" type="hidden" value="PUT">
        <div class="col-lg-8 form-group">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input class="form-control" type="text" value="{{$template->name}}" name="name"/>
            </div>
            <div class="form-group">
                <label class="control-label">Subject</label>
                <input class="form-control" type="text" value="{{$template->subject}}" name="subject"/>
            </div>
            <div class="form-group">
                <label class="control-label">Alias name</label>
                <input class="form-control" type="text" value="{{$template->alias_name}}" name="alias_name"/>
            </div>
            <div class="form-group">
                <label class="control-label">Body html</label>
                <textarea rows="15" class="form-control summernote" name="body_html"
                          value="{{$template->body_html}}">{{$template->body_html}}</textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="form-group">
                <label class="control-label">From name</label>
                <input class="form-control" type="text" value="{{$template->from_name}}" name="from_name"/>
            </div>
            <div class="form-group">
                <label class="control-label">From address</label>
                <input class="form-control" type="text" value="{{$template->from_addres}}" name="from_addres"/>
            </div>
            <div class="form-group">
                <label class="control-label">Reply-to name</label>
                <input class="form-control" type="text" value="{{$template->reply_to_name}}" name="reply_to_name"/>
            </div>
            <div class="form-group">
                <label class="control-label">Reply-to address</label>
                <input class="form-control" type="text" value="{{$template->reply_to_addres}}" name="reply_to_addres"/>
            </div>
            <div class="form-group">
                <label class="control-label">Usable Fields</label>
                <div>
                    {{$template->fields}}
                </div>
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom:120px">
            <input class="btn btn-primary" value="Save" type="submit">
        </div>
    </form>
@stop
