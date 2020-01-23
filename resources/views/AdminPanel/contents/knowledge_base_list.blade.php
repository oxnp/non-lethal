@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Knowledge base</h1>
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('knowledge-base.create')}}">
            Add item
        </a>
        <a class="btn btn-primary btn-md" href="{{route('knowledge-base-categories.index')}}">
            Categories
        </a>
    </div>
    @php
        $cats = array();
    @endphp
    @foreach($knowledge_base as $item)
        @php array_push($cats,$item->name_category) @endphp
    @endforeach
    @php
        $cats = array_unique($cats);
    @endphp
    <div class="container-fluid">
        @foreach($cats as $key=>$value)
            <h3>{{$value}}</h3>
            @foreach($knowledge_base as $item)
                @if($item->name_category == $value)
                <div class="item">
                    <a href="{{route('knowledge-base.show',$item->id)}}">{{$item->title}}</a>
                </div>
                @endif
            @endforeach
        @endforeach
    </div>
@endsection
