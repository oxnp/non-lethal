@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>News</h1>
    </section>
    <section id="pcat">
        <div class="container">
            <div class="items">
                @foreach($news as $page)
                    <div class="item row align-items-center">
                        <div class="col-md-4">
                            <a href="{{localeMiddleware::getLocaleFront()}}/{{env('NEWS_URL')}}/{{$page['slug']}}">
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
                            <a class="readmore" href="{{localeMiddleware::getLocaleFront()}}/{{env('NEWS_URL')}}/{{$page['slug']}}">
                                See More <img src="/images/blue_arr.png">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            {{$news->links()}}
        </div>
    </section>
@endsection
