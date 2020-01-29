@extends('layouts.app-front')
@section('app-front-content')

    <section class="intro login">
        <h1>My account</h1>
    </section>
    <section class="login-section">
        <form id="logme" class="m-auto" method="POST" action="{{ route('login') }}">
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
    <script>
        jQuery('form#logme').submit(function (e) {
            e.preventDefault();
            jQuery.ajax({
                url: '{{route('login')}}',
                method: 'post',
                data: jQuery(this).serialize(),
                dataType: 'json'
            })
                .fail(function (data) {
                    if (JSON.parse(data.responseText).errors.username != undefined) {
                        $('form#logme button[type="submit"]').after('<div class="err">' + JSON.parse(data.responseText).errors.username[0] + '</div>');
                    } else if (JSON.parse(data.responseText).errors.email != undefined) {
                        $('form#logme button[type="submit"]').after('<div class="err">' + JSON.parse(data.responseText).errors.email[0] + '</div>');
                    }
                })
                .done(function (data) {
                    window.location.href = "{{ route('my-licenses') }}";
                })
        });
    </script>
@endsection
