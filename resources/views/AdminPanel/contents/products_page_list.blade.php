@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div style="padding: 15px">
        <a class="btn btn-primary btn-md" href="{{route('products-pages-categories.index')}}">
            Categories
        </a>
    </div>
    <div class="container-fluid">
        @foreach($products_pages as $page)
            <div class="item">
                <a href="{{route('products-pages.show',$page->id)}}">{{$page->title}}</a>
            </div>
        @endforeach
    </div>
@endsection
