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
        </div>
    </section>
    <section id="user_stories">
        <div class="container">
            <div class="heading">
                User stories
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
                    <input type="email" name="email" required="required" placeholder="Enter email address" />
                    <button type="submit">
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <use xlink:href="#mail-envelope" x="0" y="0" />
                        </svg>
                        Send
                    </button>
                </form>
            </div>
        </div>
    </section>
@stop


