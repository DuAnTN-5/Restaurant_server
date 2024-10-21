<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HightFive Restaurant | 404 Error</title>

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center animated fadeInDown">
        <h1 style="color: red;">404</h1>
        <h3 class="font-bold">Page Not Found</h3>

        <div style="color:red;" class="error-desc">
            Sorry, Bạn không phải Admin của HightFive Restaurant nên bạn sẽ không thể truy cập vào website này.
            <br>
            <a href="{{ url('/login') }}" class="btn btn-secondary m-t">Trở về đăng nhập</a> 
        </div>
    </div>

    
    <script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script> 
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script> 

</body>

</html>
