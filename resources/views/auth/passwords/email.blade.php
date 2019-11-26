@extends('layouts.app-front')
@section('app-front-content')

    <section class="intro login">
        <h1>My account</h1>
    </section>
    <section class="login-section">
        <form class="m-auto" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="group">
                <label for="email">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="group">
                <button type="submit">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </form>
    </section>
@endsection
