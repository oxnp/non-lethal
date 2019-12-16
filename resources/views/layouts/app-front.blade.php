<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,500,600,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600 ,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/front.css" />
</head>
<body>
    @include('layouts.app-front-header')
    @yield('app-front-content')
    @include('layouts.app-front-footer')
    <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].'/public/images/svgs.svg');?>
    <script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script src="https://cdn.paddle.com/paddle/paddle.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/functions.js"></script>
</body>
</html>
