@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>{{$product_data[0]['title']}}</h1>
    </section>
    <section id="prod_page">
        <div class="container">
            <div class="desc">
                {!! $product_data[0]['content'] !!}
            </div>
        </div>
    </section>
@endsection
