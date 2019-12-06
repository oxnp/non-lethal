@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro kb">
        <h1>News</h1>
    </section>
    <section id="kb_item">
        <div class="container">
            <div class="desc">
                {!!$news[0]['content']!!}
            </div>
        </div>
    </section>
@endsection
