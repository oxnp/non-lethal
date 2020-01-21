@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>{{$category_data['category'][0]['name']}}</h1>
        <div class="subtitle">
            {{$category_data['category'][0]['sub_name']}}
        </div>
    </section>
    <section id="pcat" class="cat_{{$category_data['category'][0]['id']}}">
        @if(count($category_data['pages'])>0)
            <div class="container">
                <div class="items">
                    @foreach($category_data['pages'] as $page)
                        <div class="item row align-items-center">
                            <div class="col-md-4">
                                <a href="{{localeMiddleware::getLocaleFront()}}/{{env('PRODUCTS_URL')}}/{{$category}}/{{$page['slug']}}">
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
                                <a class="readmore" href="{{localeMiddleware::getLocaleFront()}}/{{env('PRODUCTS_URL')}}/{{$category}}/{{$page['slug']}}">
                                    {!!trans('main.see_more')!!} <img src="/images/blue_arr.png">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {!! $category_data['category'][0]['content'] !!}

            <div class="modal fade" id="prodlog" tabindex="-1" role="dialog"
                 aria-labelledby="prodlog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            {{trans('main.login_please')}}
                        </div>
                        <div class="modal-body text-center">
                            <section class="login-section">
                                <form class="m-auto" method="POST" id="prlogin" action="{{ route('login') }}">
                                    @csrf
                                    <div class="group">
                                        <label for="email">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="login"
                                               value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                        @enderror
                                    </div>
                                    <div class="group">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror" name="password"
                                               required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                        @enderror
                                    </div>
                                    <div class="group">
                                        <input type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    <div class="group">
                                        <button type="submit">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                    <div class="group">
                                        <div class="other">
                                            @if (Route::has('password.request'))
                                                <div>
                                                    <a href="{{ route('password.request') }}">
                                                        {{ __('Forgot Your Password?') }}
                                                    </a>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('register') }}">
                                                    {{ __('Create new account') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="activation_prod" tabindex="-1" role="dialog"
                 aria-labelledby="activation" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header nimg{{$category_data['category'][0]['id']}}">
                            <div>Download {{$category_data['category'][0]['name']}}</div>
                        </div>
                        <div class="modal-body text-center">
                            <div class="alert alert-primary" role="alert">
                                We would love to stay in touch!<br />
                                Please take a moment to signup for our newsletter.*
                            </div>
                            <form name="newsletter_prod" method="POST" action="javascript:void(0)">
                                {{csrf_field()}}
                                <input style="display: block;width: 100%;margin-bottom: 15px" @if(!Auth::guest()) value="{{Auth::user()->email}}" readonly="readonly" @endif type="email" name="email" placeholder="{!!trans('main.enter_email')!!}"/>
                                <div class="sp">* Signup is optional and not required for downloading the software.</div>
                                <button type="submit" style="margin: 15px auto">
                                    <svg width="20" height="20" viewBox="0 0 20 20">
                                        <use xlink:href="#mail-envelope" x="0" y="0"/>
                                    </svg>
                                    {!!trans('main.download')!!}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $('a[href^="/nlalib"]').click(function (e) {
                    e.preventDefault();
                    let file = $(this).attr('href');
                    $('#activation_prod').modal('show');
                    jQuery('form[name="newsletter_prod"]').attr('data-link',file);
                })
                jQuery('form[name="newsletter_prod"]').submit(function(e){
                    e.preventDefault();
                    let file = $(this).attr('data-link');
                    if($(this).find('input[name="email"]').val()!=='') {
                        jQuery.ajax({
                            url: '{{route('newsletterSendFront')}}',
                            method: 'post',
                            data: jQuery(this).serialize(),
                            dataType: 'json'
                        })
                            .done(function (data) {
                                if (data == true) {
                                    window.location.href = file;
                                    $('#activation_prod').modal('hide');
                                } else {
                                    jQuery('form[name="newsletter_prod"]').append('<div class="alert alert-warning" role="alert">\n' +
                                        '  {{trans("main.already_subscribed")}}\n' +
                                        '</div>');
                                }
                                setTimeout(function () {
                                    jQuery('form[name="newsletter_prod"] .alert').fadeOut();
                                }, 3000)
                            })
                    }else{
                        window.location.href = file;
                        $('#activation_prod').modal('hide');
                    }
                })
            </script>
        @endif
    </section>
@endsection
