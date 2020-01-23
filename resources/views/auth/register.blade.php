@extends('layouts.app-front')
@section('app-front-content')

    <section class="intro login">
        <h1>My account</h1>
    </section>
    <section class="register">
        <div class="container">
            <form id="register" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="formhead">
                    {{ __('User registration') }}
                </div>
                <div class="req">
                    * Required field
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="name" class="col-form-label text-md-right">{{ __('First Name') }} *</label>
                    <div class="col">
                        <input id="name" type="text"
                               class="form-control @error('name') is-invalid @enderror" name="name"
                               value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="last_name" class="col-form-label text-md-right">Last Name *</label>
                    <div class="col">
                        <input id="last_name" type="text"
                               class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                               value="{{ old('last_name') }}" required autocomplete="name" autofocus>
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                </div>
                <!--<div class="form-group row flex-nowrap">
                    <label for="username" class="col-form-label text-md-right">{{ __('Username') }} *</label>
                    <div class="col">
                    <input id="username" type="text"
                           class="form-control @error('username') is-invalid @enderror" name="username"
                           value="{{ old('username') }}" required autocomplete="username" autofocus>
                    </div>
                </div>-->

                <div class="form-group row flex-nowrap">
                    <label for="password"
                           class="col-form-label text-md-right">{{ __('Password') }} *</label>
                    <div class="col">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               required autocomplete="new-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row flex-nowrap">
                    <label for="password-confirm"
                           class="col-form-label text-md-right">{{ __('Confirm Password') }} *</label>
                    <div class="col">
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="email"
                           class="col-form-label text-md-right">{{ __('E-Mail Address') }} *</label>
                    <div class="col">
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                    </div>
                </div>
                <!--<div class="form-group row flex-nowrap">
                    <label for="email_confirm"
                           class="col-form-label text-md-right">{{ __('Confirm E-Mail Address') }} *</label>
                    <div class="col">
                    <input id="email_confirm" type="email"
                           class="form-control @error('email_confirm') is-invalid @enderror" name="email_confirm"
                           value="{{ old('email_confirm') }}" required autocomplete="email_confirm">
                    </div>
                </div>-->
                <div class="form-group row mb-0">
                    <label></label>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="success_reg" tabindex="-1" role="dialog"
         aria-labelledby="success_reg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Success!</h2><br />
                    You will get a confirmation mail very soon!<br />
                    Don't forget to check your SPAM folder if the mail is not in your inbox for a while.<br /><br />
                    <div><a class="btn btn-primary" href="{{ route('my-licenses') }}">My licenses</a></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery('form#register').submit(function (e) {
            e.preventDefault();
            let pass = $(this).find('input#password').val();
            let pass_c = $(this).find('input#password-confirm').val();
            if(pass == pass_c) {
                $('input#password,input#password-confirm').removeClass('is-invalid');
                jQuery.ajax({
                    url: '{{route('register')}}',
                    method: 'post',
                    data: jQuery(this).serialize(),
                    dataType: 'json'
                })
                    .fail(function (data) {
                        $('.err').each(function () {
                            $(this).remove();
                        })
                        if (JSON.parse(data.responseText).errors.password != undefined) {
                            $('input#password,input#password-confirm').addClass('is-invalid');
                            $('form#register button[type="submit"]').before('<div class="err">' + JSON.parse(data.responseText).errors.password[0] + '</div>');
                        }
                        if (JSON.parse(data.responseText).errors.email != undefined) {
                            $('input#email').addClass('is-invalid');
                            $('form#register button[type="submit"]').before('<div class="err">' + JSON.parse(data.responseText).errors.email[0] + '</div>');
                        }

                    })
                    .done(function (data) {
                        $('#success_reg').modal('show');
                    })
            }else{
                $('input#password,input#password-confirm').addClass('is-invalid');
                $('form#register button[type="submit"]').before('<div class="err">Password mismatch</div>');
            }
        });
    </script>
@endsection
