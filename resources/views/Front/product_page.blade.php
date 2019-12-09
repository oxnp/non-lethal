@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>{{$product_data[0]['title']}}</h1>
        <div class="subtitle">
            {{$product_data[0]['sub_title']}}
        </div>
    </section>
    <section id="pcat" class="cat_{{$product_data[0]['id']}}">
        {!! $product_data[0]['content'] !!}
    </section>
@endsection
