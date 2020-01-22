@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Knowledge base categories</h1>
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('knowledge-base-categories.create')}}">
            Add category
        </a>
    </div>
    <div class="container-fluid">
        @foreach($knowledge_base_categories as $cat)
            <div class="item">
                <a href="{{route('knowledge-base-categories.show',$cat->id)}}">{{$cat->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
