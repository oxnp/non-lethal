@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')

    <div class="container-fluid">
        @foreach($static_pages as $page)
            <div class="item">
                <a href="{{route('static-pages.show',$page->id)}}">{{$page->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
