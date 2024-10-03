<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HIGHTFIVE</title>
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
                    
                    <form class="m-t" method="post" role="form" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{old('email')}}">
                            @if ($errors->has('email'))
                                <span class="error-message">* {{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="error-message">* {{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                        <a href="{{route('forgotPassword')}}">
                            <small>Forgot password?</small>
                        </a>

                        <p class="text-muted text-center">
                            <small>Do not have an account?</small>
                        </p>
                        <!-- Update the href to use the named route for register -->
                        <a class="btn btn-sm btn-white btn-block" href="{{ route('register') }}">Create an account</a>
                        <div class="social-login-buttons">
                            <a href="{{ url('auth/facebook') }}" class="btn-social btn-facebook">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="{{ url('auth/google')}}" class="btn-social btn-google">
                                <i class="fab fa-google"></i> Google
                            </a>
                        </div>
                        
                    </form>
                    <p class="m-t"><small>Trần Minh Quân  &copy; 2024</small></p>
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
<script>
    async function redirectToGoogle() {
        try {
            const response = await fetch('{{ url("api/auth/google/url") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
            });
            const data = await response.json();
            if (response.ok) {
                window.location.href = data.url;  // Redirect to Google Sign-In URL
            } else {
                alert('Failed to get Google Sign-In URL: ' + data.error);
            }
        } catch (error) {
            console.error('Error fetching Google Sign-In URL:', error);
        }
    }
</script>