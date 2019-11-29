@section('header')
    <div class="topmenu row">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('buyers.index')}}">
                        Buyers
                    </a>
                </li>
                <li class="nav-item d-inline">
                    <a class="nav-link" href="{{route('products.index')}}">
                        Products
                    </a>
                </li>
                <li class="nav-item d-inline">
                    <a class="nav-link" href="{{route('precodes.index')}}">
                        Pre-codes
                    </a>
                </li>
                <li class="nav-item d-inline">
                    <a class="nav-link" href="{{route('ilok_codes.index')}}">
                        Ilok codes
                    </a>
                </li>
                <li class="nav-item d-inline">
                    <a class="nav-link" href="{{route('licenses.index')}}">
                        Licenses
                    </a>
                </li>
            </ul>
        </div>
    </div>
@stop
