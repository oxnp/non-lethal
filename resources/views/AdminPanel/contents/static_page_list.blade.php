@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div style="padding: 15px">
        <!--<a class="btn btn-primary btn-md" href="{{route('static-pages.create')}}">
            Add item
        </a>-->
    </div>
    <div class="container-fluid">
        @foreach($static_pages as $page)
            <div class="item">
                <a href="{{route('static-pages.show',$page->id)}}">{{$page->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
