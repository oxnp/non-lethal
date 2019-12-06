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
                                        <a href="/{{env('PRODUCTS_URL')}}/{{$cat['slug']}}">{{$cat['name']}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <a href="/user-stories">User Stories</a>
                        </li>
                        <li class="parent">
                            <a href="#">Support</a>
                            <ul class="child">
                                <li>
                                    <a href="/support/knowledge-base">Knowledge base</a>
                                </li>
                                <li>
                                    <a href="/support">Get in touch</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="/partners">Partners</a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}">My account</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
