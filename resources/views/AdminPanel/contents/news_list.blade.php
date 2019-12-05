@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('news.create')}}">
            Add news
        </a>
    </div>
    <div class="container-fluid">
        @foreach($news as $item)
            <div class="item">
                <a href="{{route('news.show',$item->id)}}">{{$item->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
