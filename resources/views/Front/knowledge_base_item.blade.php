@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro kb">
        <h1>Knowledge base</h1>
    </section>
    <section id="kb_item">
        <div class="container">
            <div class="desc">
                {!!$content!!}
            </div>
        </div>
    </section>
@endsection
