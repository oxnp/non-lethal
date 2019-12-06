@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>{{$category_data['category'][0]['name']}}</h1>
        <div class="subtitle">
            {{$category_data['category'][0]['sub_name']}}
        </div>
    </section>
    <section id="pcat" class="cat_{{$category_data['category'][0]['id']}}">
        @if(count($category_data['pages'])>0)
            <div class="container">
                <div class="items">
                    @foreach($category_data['pages'] as $page)
                        <div class="item row align-items-center">
                            <div class="col-md-4">
                                <a href="/{{env('PRODUCTS_URL')}}/{{$category}}/{{$page['slug']}}">
                                    <img src="{{$page['image']}}">
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="title">
                                    {{$page['title']}}
                                </div>
                                <div class="subtitle">
                                    {{$page['sub_title']}}
                                </div>
                                <div class="minidesc">
                                    {{$page['short_text']}}
                                </div>
                                <a class="readmore" href="/{{env('PRODUCTS_URL')}}/{{$category}}/{{$page['slug']}}">
                                    See More <img src="/images/blue_arr.png">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {!! $category_data['category'][0]['content'] !!}
        @endif
    </section>
@endsection
