@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro kb">
        <h1>Knowledge base</h1>
    </section>
    <section id="kb">
        <div class="container">
            <div class="desc">
                <h2 class="text-center">Articles</h2>
                <div class="cats row justify-content-around">
                    @foreach($data['categories'] as $cat)
                        <a href="{{$cat['url']}}">{{$cat['name']}}</a>
                    @endforeach
                </div>
                <div class="items">
                    @foreach($data['list'] as $item)
                        <div class="item row align-items-center">
                            <div class="col-md-4">
                                <a href="{{$item['url']}}">
                                    <img src="{{$item['image']}}">
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="title">
                                    {{$item['title']}}
                                </div>
                                <div class="minidesc">
                                    {{$item['content']}}
                                </div>
                                <a class="readmore" href="{{$item['url']}}">
                                    See More <img src="/images/blue_arr.png">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
