<header>
    <div class="container">
        <div class="row no-gutters">
            <div class="col-lg-5">
                <a class="logo" href="/">
                    <img src="/images/logo.png">
                </a>
            </div>
            <div class="col-lg-7 align-self-center">
                <div class="topmenu text-right">
                    <ul class="row justify-content-between">
                        <li class="parent">
                            <a href="#">Products</a>
                            <ul class="child">
                                @foreach($categories as $cat)
                                    <li>
                                        <a href="{{localeMiddleware::getLocale()}}/{{env('PRODUCTS_URL')}}/{{$cat['slug']}}">{{$cat['name']}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <a href="{{localeMiddleware::getLocale()}}/user-stories">User Stories</a>
                        </li>
                        <li class="parent">
                            <a href="#">Support</a>
                            <ul class="child">
                                <li>
                                    <a href="{{localeMiddleware::getLocale()}}/support/knowledge-base">Knowledge
                                        base</a>
                                </li>
                                <li>
                                    <a href="{{localeMiddleware::getLocale()}}/support">Get in touch</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{localeMiddleware::getLocale()}}/partners">Partners</a>
                        </li>
                        @if(Auth::guest())
                            <li>
                                <a href="{{ route('login') }}">My account</a>
                            </li>
                        @else
                            <li class="parent rt">
                                <a href="{{ route('my-licenses') }}">My account</a>
                                <ul class="child">
                                    <li>
                                        <a href="{{ route('my-licenses') }}">My licenses</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile') }}">Edit profile</a>
                                    </li>
                                    <li>
                                        <a href="{{route('logout')}}">Log out</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
