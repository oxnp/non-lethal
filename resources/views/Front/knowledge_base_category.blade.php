@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro kb">
        <h1>{!!trans('main.knowledge_base')!!}</h1>
        @if(isset($data['sub_title_page']))
            <div class="subtitle">
                {{$data['sub_title_page']}}
            </div>
        @endif
    </section>
    <section id="kb">
        <div class="container">
            <div class="desc">
                <h2 class="text-center">{!!trans('main.articles')!!}</h2>
                <div class="cats row justify-content-around">
                    <div class="col text-center">
                        <a
                            @if(localeMiddleware::getLocaleFront().$data['all_item_url']==$_SERVER['REQUEST_URI'])
                            class="active"
                            @endif
                            href="{{localeMiddleware::getLocaleFront()}}{{$data['all_item_url']}}">{!!trans('main.all')!!}</a>
                    </div>
                    @foreach($data['categories'] as $cat)
                        @if($cat['visible']!=0)
                        <div class="col text-center">
                            <a
                                @if(localeMiddleware::getLocaleFront().$cat['url']==$_SERVER['REQUEST_URI'])
                                    class="active"
                                @endif
                                href="{{localeMiddleware::getLocaleFront()}}{{$cat['url']}}">{{$cat['name']}}</a>
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="items">
                    @foreach($data['list'] as $item)
                        <div class="item row align-items-center">
                            <div class="col-md-4">
                                <a href="{{localeMiddleware::getLocaleFront()}}{{$item['url']}}">
                                    <img src="{{$item['image']}}">
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="title">
                                    {{$item['title']}}
                                </div>
                                <div class="minidesc">
                                    {{str_replace($item['title'],'',$item['content'])}}
                                </div>
                                <a class="readmore" href="{{localeMiddleware::getLocaleFront()}}{{$item['url']}}">
                                    {!!trans('main.see_more')!!} <img src="/images/blue_arr.png">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
