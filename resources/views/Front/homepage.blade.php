@extends('layouts.app-front')
@section('app-front-content')
    <section id="slider" class="text-center">
        <div class="container h-100">
            <div class="row h-100 align-content-center">
                <div class="owl-carousel slider col">
                    <div class="item">
                        <div class="text">
                            SIX free programs to support your creativity
                        </div>
                        <div class="subtext">
                            You can definitely choose something useful for yourself!
                        </div>
                    </div>
                    <div class="item">
                        <div class="text">
                            SIX free programs to support your creativity
                        </div>
                        <div class="subtext">
                            You can definitely choose something useful for yourself!
                        </div>
                    </div>
                </div>
                <div class="social">
                    <a href="#" target="_blank" rel="nofollow">
                        <svg width="26" height="24" viewBox="0 0 26 24">
                            <use xlink:href="#youtube" x="0" y="0" />
                        </svg>
                    </a>
                    <a href="#" target="_blank" rel="nofollow">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <use xlink:href="#instagram" x="0" y="0" />
                        </svg>
                    </a>
                    <a href="#" target="_blank" rel="nofollow">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <use xlink:href="#facebook" x="0" y="0" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section id="create" class="text-center">
        <div class="container">
            <div class="heading">
                Create with pleasure<br/>
                and convenience
            </div>
            <div class="row">
                <div class="col">
                    <img src="/images/monitor.png">
                    <div class="desc">
                        Convenient and intuitive<br/>interface
                    </div>
                </div>
                <div class="col">
                    <img src="/images/man.png">
                    <div class="desc">
                        You will receive excellent information support
                    </div>
                </div>
                <div class="col">
                    <img src="/images/function.png">
                    <div class="desc">
                        Modern and Useful<br/>Functionality
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="latest_news">
        <div class="container">
            <div class="heading">
                Latest news
            </div>



            <div class="owl-carousel news_slider">
                @foreach($news as $item)
                <div class="item col-12">
                    <div class="inner">
                        <div class="pad">
                            <div class="addpad">
                                <!--<div class="cat">
                                    <span class="num">01</span>
                                </div>-->
                                <div class="title">
                                    {{$item->title}}
                                </div>
                                <div class="desc">
                                    {{substr(strip_tags($item->content),0,100)}}
                                </div>
                            </div>
                            <img src="{{$item->image}}">
                        </div>
                        <a href="{{localeMiddleware::getLocaleFront()}}/{{env('NEWS_URL')}}/{{$item->slug}}" class="readmore">More Detailed <img src="/images/readmore_arr.png"></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section id="user_stories">
        <div class="container">
            <div class="heading">
                User stories
            </div>
            <div class="owl-carousel user_stories">

                @foreach($user_stories as $story)
                <div class="item row align-items-center">
                    <div class="col-lg-5">
                        <div class="title">
                            {{$story->title}}
                        </div>
                        <div class="subtitle">
                            {{$story->sub_title}}
                        </div>
                        <div class="desc">
                            {{substr(strip_tags($story->content),0,300)}}
                        </div>
                        <a class="readmore" href="{{localeMiddleware::getLocaleFront()}}/{{env('USER_STORIES_URL')}}/{{$story->slug}}">See More <img src="/images/blue_arr.png"></a>
                    </div>
                    <div class="col-lg-7">
                        <img src="{{$story->image}}" class="shad ml-auto">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section id="newsletter" class="text-center">
        <div class="container">
            <div class="heading">
                Join newsletter
            </div>
            <div class="subtext">
                Stay up to date
            </div>
            <div class="col-lg-6 m-auto">
                <form name="newsletter" method="POST" action="javascript:void(0)">
                    <input type="email" name="email" required="required" placeholder="Enter email address"/>
                    <button type="submit">
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <use xlink:href="#mail-envelope" x="0" y="0"/>
                        </svg>
                        Send
                    </button>
                </form>
            </div>
        </div>
    </section>
@stop


