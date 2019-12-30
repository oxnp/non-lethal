@section('left-sidebar')
    <nav>
        <ul class="nav">
            <li><a href="{{route('licenses.index')}}">Licenses</a></li>
            <li><a href="{{route('static-pages.index')}}">Static pages</a></li>
            <li><a href="{{route('products-pages.index')}}">Products pages</a></li>
            <li><a href="{{route('news.index')}}">News</a></li>
            <li><a href="{{route('user-stories.index')}}">User stories</a></li>
            <li><a href="{{route('knowledge-base.index')}}">Knowledge base</a></li>
            <li><a href="{{route('subscribers.index')}}">Newsletters</a></li>
        </ul>
    </nav>
@stop

