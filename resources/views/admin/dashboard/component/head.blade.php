<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HightFive Admin Panel">
    <meta name="author" content="Your Name or Company">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ADMIN | HightFive</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/plugins/morris/morris-0.4.3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    {{-- Thẻ này sẽ thêm favicon.ico vào website --}}
<link rel="shortcut icon" href="{{ asset('backend/img/favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('backend/img/favicon.ico') }}" type="image/x-icon">

    @if (isset($config['css']) && is_array($config['css']))
    @foreach ($config['css'] as $key => $val)
        {!! '<link rel="stylesheet" href="' . $val . '"></link>' !!}            
    @endforeach
@endif
    <!-- Optional: Toastr notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
