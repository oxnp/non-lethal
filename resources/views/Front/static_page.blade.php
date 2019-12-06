@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro statpage {{$content[0]['slug']}}"
             @if(!empty($content[0]['image']))
             style="background: url('{{$content[0]['image']}}')no-repeat center;background-size:cover;"
        @endif
    >
        <h1>{{$content[0]['title']}}</h1>
    </section>
    <section id="{{$content[0]['slug']}}">
        <div class="container">
            {!!$content[0]['content']!!}
        </div>
    </section>
@endsection
