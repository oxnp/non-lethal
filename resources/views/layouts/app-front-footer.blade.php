@if (Route::getCurrentRoute()->uri() !== '/')
    <section id="breadcrumbs">
        <div class="container">
                {!!trans('main.you_are_here')!!}:
                <span class="divider"> <img src="/images/bread_divider.png"> </span>
                <a href="/">{!!trans('main.homepage')!!}</a>
        </div>
    </section>
@endif
<footer>
    <div class="about_nla">
        <div class="container">
            <div class="heading">
                {!!trans('main.about_nla')!!}
            </div>
            <div class="footmenu row text-center">
                <div class="col">
                    <a href="{{localeMiddleware::getLocaleFront()}}/company">{!!trans('main.company')!!}</a>
                </div>
                <div class="col">
                    <a href="{{localeMiddleware::getLocaleFront()}}/impressum">{!!trans('main.impressum')!!}</a>
                </div>
                <div class="col">
                    <a href="{{localeMiddleware::getLocaleFront()}}/disclaimer">{!!trans('main.disclaimer')!!}/{!!trans('main.privacy_policy')!!}</a>
                </div>
            </div>
            <div class="row social">
                <div class="col-lg-2 m-auto row justify-content-between">
                    <a href="https://twitter.com/nonlethalapp" target="_blank" rel="nofollow">
                        <svg width="26" height="24" viewBox="0 0 26 24">
                            <use xlink:href="#twitter" x="0" y="0"/>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/company/10368426/" target="_blank" rel="nofollow">
                        <svg width="26" height="24" viewBox="0 0 26 24">
                            <use xlink:href="#linkedin" x="0" y="0"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/nonlethalapplications/" target="_blank" rel="nofollow">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <use xlink:href="#instagram" x="0" y="0"/>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/NonLethalApplications/" target="_blank" rel="nofollow">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <use xlink:href="#facebook" x="0" y="0"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright text-center">
        {!!trans('main.copyright')!!}
    </div>
</footer>
