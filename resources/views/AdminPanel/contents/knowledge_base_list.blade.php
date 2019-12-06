@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('knowledge-base.create')}}">
            Add item
        </a>
        <a class="btn btn-primary btn-md" href="{{route('knowledge-base-categories.index')}}">
            Categories
        </a>
    </div>
    <div class="container-fluid">
        @foreach($knowledge_base as $item)
            <div class="item">
                <a href="{{route('knowledge-base.show',$item->id)}}">{{$item->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
