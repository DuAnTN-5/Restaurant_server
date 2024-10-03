<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | HIGHTFIVE</title>
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/customize.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    
</head>
<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">Welcome to <span style="color: #17a589;">HIGHTFIVE Restaurant+</span></h2>
                <img style="height: 300px;" src="{{ asset('logo/LogoPNG.png') }}" alt="Logo">
                <p><small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small></p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" method="post" role="form" action="{{ route('password.email') }}">
                        @csrf
                        <h3>Reset Password</h3>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Email"
                                value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="error-message">*
                                    {{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary block full-width m-b">Send Password Reset
                            Link</button>
                    </form>
                    <p class="m-t"><small>Trần
                            Minh Quân  © 2024</small></p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">Copyright Hight Five Group</div>
            <div class="col-md-6 text-right"><small>© 2003-2024</small></div>
        </div>
    </div>
</body>
</html>
