<!doctype html>
<html>
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <script src="/js/chosen.jquery.min.js"></script>
    <script src="/js/summernote.min.js"></script>
    <script src="/js/functions-admin.js"></script>



    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext"
        rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link href="/css/summernote.css" rel="stylesheet">
    <link href="/css/chosen.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7dfad927b2.js"></script>
</head>
<body>
<div class="col-lg-2 sidebar">
    @yield('left-sidebar')
</div>
<div class="col-lg-10 col-lg-offset-2">
    <div class="header">
        @yield('header')
    </div>
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
