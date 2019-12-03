@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro {{$content[0]['slug']}}">
        <h1>{{$content[0]['title']}}</h1>
    </section>
    <section id="{{$content[0]['slug']}}">
        <div class="container">
            {!!$content[0]['content']!!}
        </div>
    </section>
@endsection
