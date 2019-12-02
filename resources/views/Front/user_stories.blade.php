@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro stories">
        <h1>User stories</h1>
    </section>
    <section id="stories">
        <div class="container">
            <div class="stories_vid">
                <iframe width="640" height="360"
                        src="//player.vimeo.com/video/226872860?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"
                        allowfullscreen=""></iframe>
            </div>
            <div class="items row">
                @foreach ($user_stories as $user_story)
                    <div class="item col-md-3">
                        <a href="{{env('USER_STORIES_URL')}}/{{$user_story['slug']}}"><img
                                src="{{$user_story['image']}}"></a>
                        <div class="title">
                            <a href="{{env('USER_STORIES_URL')}}/{{$user_story['slug']}}">{{$user_story['title']}}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
