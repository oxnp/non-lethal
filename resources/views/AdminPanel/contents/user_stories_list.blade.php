@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>User stories</h1>
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('user-stories.create')}}">
            Add story
        </a>
    </div>
    <div class="container-fluid">
        @foreach($user_stories as $item)
            <div class="item">
                <a href="{{route('user-stories.show',$item->id)}}">{{$item->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
