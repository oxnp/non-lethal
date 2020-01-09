@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div class="list">
        <div class="list_head">
            <div class="col-md-1">ID</div>
            <div class="col-md-6">Name</div>
            <div class="col-md-5">Alias name</div>
        </div>
        <div class="list_body">
            @foreach($templates as $template)
                <div class="item">
                    <div class="col-md-1 idcol">{{$template->id}}</div>
                    <div class="col-md-6"><a href="{{route('emails-templates.show',$template->id)}}">{{$template->name}}</a></div>
                    <div class="col-md-5">{{$template->alias_name}}</div>
                </div>
            @endforeach
        </div>
    </div>
@stop
