<header>
    <?php $locale = App::getLocale()?>
    @if($locale == 'en')
        <a class="lang" href="{{str_replace($_SERVER["HTTP_HOST"],''.$_SERVER["HTTP_HOST"].'/de',URL::current())}}">DE</a>
    @else
        <a class="lang" href="{{str_replace(''.$_SERVER["HTTP_HOST"].'/de',$_SERVER["HTTP_HOST"],URL::current())}}">EN</a>
    @endif
    <div class="container">
        <div class="row no-gutters">
            <div class="mobmenu">MENU</div>
            <div class="col-lg-5 mlogo">
                <a class="logo" href="/{{localeMiddleware::getLocale()}}">
                    <img src="/images/logo.png">
                </a>
            </div>
            <div class="col-lg-7 align-self-center mmenu">
                <div class="topmenu text-right">
                    <ul class="row justify-content-between">
                        <li class="parent">
                            <a href="#">Products</a>
                            <ul class="child">
                                @foreach($categories as $cat)
                                    @if($cat['auth_visible']==0)
                                        <li>
                                            <a href="{{localeMiddleware::getLocaleFront()}}/{{env('PRODUCTS_URL')}}/{{$cat['slug']}}">{{$cat['name']}}</a>
                                        </li>
                                    @elseif($cat['auth_visible']==1)
                                        @if(!Auth::guest())
                                            <li>
                                                <a href="{{localeMiddleware::getLocaleFront()}}/{{env('PRODUCTS_URL')}}/{{$cat['slug']}}">{{$cat['name']}}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <a href="{{localeMiddleware::getLocaleFront()}}/user-stories">User Stories</a>
                        </li>
                        <li class="parent">
                            <a href="#">Support</a>
                            <ul class="child">
                                <li>
                                    <a href="{{localeMiddleware::getLocaleFront()}}/support/knowledge-base">Knowledge
                                        base</a>
                                </li>
                                <li>
                                    <a href="{{localeMiddleware::getLocaleFront()}}/support">Get in touch</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{localeMiddleware::getLocaleFront()}}/partners">Partners</a>
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
