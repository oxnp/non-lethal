@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>{{$product_data[0]['title']}}</h1>
        <div class="subtitle">
            {{$product_data[0]['sub_title']}}
        </div>
    </section>
    <section id="pcat" class="cat_{{$product_data[0]['id']}}">
        {!! $product_data[0]['content'] !!}
    </section>
    <div class="modal fade" id="activation_prod" tabindex="-1" role="dialog"
         aria-labelledby="activation" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header pnimg{{$product_data[0]['id']}}">
                    <div>Download {{$product_data[0]['title']}}</div>
                </div>
                <div class="modal-body text-center">
                    <div class="alert alert-primary" role="alert">
                        We would love to stay in touch!<br/>
                        Please take a moment to signup for our newsletter.*
                    </div>
                    <form name="newsletter_prod" method="POST" action="javascript:void(0)">
                        {{csrf_field()}}
                        <input style="display: block;width: 100%;margin-bottom: 15px"
                               @if(!Auth::guest()) value="{{Auth::user()->email}}" readonly="readonly"
                               @endif type="email" name="email" placeholder="{!!trans('main.enter_email')!!}"/>
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

    <div class="modal fade" id="subs_text" tabindex="-1" role="dialog"
         aria-labelledby="subs_text" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>One Year, Paid Monthly plan</h2>
                    <h4>with a one year contract</h4>
                    <p style="text-align: justify;">Service begins as soon as your initial payment is processed. You'll
                        be charged the rate stated at the time of purchase, plus applicable taxes (such as value added
                        tax when the stated rate doesn't include VAT), every month for the duration of your annual
                        contract.</p>
                    <p style="text-align: justify;">Your contract will renew automatically, on your annual renewal date,
                        until you cancel. The contract can be cancelled any time by logging into our website. However,
                        it will only take effect at the end of the subscription year. Renewal rates are subject to
                        change, but we'll always notify you beforehand.</p>
                    <p style="text-align: justify;">Video Slave needs to refresh the license once at the end of each
                        month. For this, a working internet connection is required. If no internet connection is
                        available, Video Slave will return to demo mode.</p>
                    <p style="margin-top: 30px; margin-bottom: 20px;"><b>By clicking the accept button you agree to the
                            billing terms above.</b></p>
                    <button class="remodal-cancel" data-remodal-action="cancel">Cancel</button>
                    <button class="remodal-confirm" data-remodal-action="confirm">Accept Terms &amp; Conditions</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="prodlog" tabindex="-1" role="dialog"
         aria-labelledby="prodlog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    {{trans('main.login_please')}}
                </div>
                <div class="modal-body text-center">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="logme-tab" data-toggle="tab" href="#logme" role="tab"
                               aria-controls="logme" aria-selected="true">Existing user</a>
                        </li>
                        <li>
                            <a class="nav-link" id="regme-tab" data-toggle="tab" href="#regme" role="tab"
                               aria-controls="regme" aria-selected="true">New user</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="logme" role="tabpanel" aria-labelledby="log-tab">
                            <section class="login-section">
                                <form class="m-auto" method="POST" id="prlogin" action="{{ route('login') }}">
                                    @csrf
                                    <div class="group">
                                        <label for="email">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               name="login"
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
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password"
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
                        <div class="tab-pane fade" id="regme" role="tabpanel" aria-labelledby="reg-tab">
                            <section class="login-section">
                                <form id="ajaxreg" method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="group">
                                        <label for="name">First name *</label>
                                        <input id="name" type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="group">
                                        <label for="lastname">Last name *</label>
                                        <input id="lastname" type="text" class="form-control" name="last_name" required>
                                    </div>
                                    <div class="group">
                                        <label for="emails">E-mail *</label>
                                        <input id="emails" type="email" class="form-control" name="email">
                                    </div>
                                    @php $passrand = str_random(12); @endphp
                                    <input type="hidden" value="{{$passrand}}" name="password">
                                    <input type="hidden" value="{{$passrand}}" name="password_confirmation">
                                    <div class="req text-left" style="margin-bottom: 10px;">* Required fields</div>
                                    <div class="group">
                                        <button type="submit">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $('a[href^="/nlalib"]').click(function (e) {
            e.preventDefault();
            let file = $(this).attr('href');
            $('#activation_prod').modal('show');
            jQuery('form[name="newsletter_prod"]').attr('data-link', file);
        })
        jQuery('form[name="newsletter_prod"]').submit(function (e) {
            e.preventDefault();
            let file = $(this).attr('data-link');
            if ($(this).find('input[name="email"]').val() !== '') {
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
                            window.location.href = file;
                            $('#activation_prod').modal('hide');
                        }
                        setTimeout(function () {
                            jQuery('form[name="newsletter_prod"] .alert').fadeOut();
                        }, 3000)
                    })
            } else {
                window.location.href = file;
                $('#activation_prod').modal('hide');
            }
        })
    </script>
@endsection
