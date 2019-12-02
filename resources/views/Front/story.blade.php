@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro stories">
        <h1>User stories</h1>
    </section>
    <section id="story_item">
        <div class="container">
            <h2 class="maintitle">{!!$user_story[0]['title']!!}</h2>
            <div class="subtitle">{!!$user_story[0]['sub_title']!!}</div>
            <div class="text-center intro_img">
                <img src="{!!$user_story[0]['image']!!}">
            </div>
            <div class="desc">
                {!!$user_story[0]['content']!!}
            </div>
        </div>
    </section>
@endsection
