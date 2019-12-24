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
                            <a href="#">{!!trans('main.products')!!}</a>
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
                            <a href="{{localeMiddleware::getLocaleFront()}}/user-stories">{{trans('main.USER_STORIES')}}</a>
                        </li>
                        <li class="parent">
                            <a href="#">{{trans('main.support')}}</a>
                            <ul class="child">
                                <li>
                                    <a href="{{localeMiddleware::getLocaleFront()}}/support/knowledge-base">{{trans('main.knowledge_base')}}</a>
                                </li>
                                <li>
                                    <a href="{{localeMiddleware::getLocaleFront()}}/support">{{trans('main.get_in_touch')}}</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{localeMiddleware::getLocaleFront()}}/partners">{{trans('main.partners')}}</a>
                        </li>
                        @if(Auth::guest())
                            <li>
                                <a href="{{ route('login') }}">{{trans('main.my_account')}}</a>
                            </li>
                        @else
                            <li class="parent rt">
                                <a href="{{ route('my-licenses') }}">{{trans('main.my_account')}}</a>
                                <ul class="child">
                                    <li>
                                        <a href="{{ route('my-licenses') }}">{{trans('main.my_licenses')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile') }}">{{trans('main.my_account')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('logout')}}">{{trans('main.logout')}}</a>
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
