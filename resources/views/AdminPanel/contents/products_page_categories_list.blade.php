@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')

    <div class="container-fluid">
        @foreach($categories_list as $cat)
            <div class="item">
                <a href="{{route('products-pages-categories.show',$cat->id)}}">{{$cat->name}}</a>
            </div>
        @endforeach
    </div>
@endsection
