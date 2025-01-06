<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>{{config('app.name')}} | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{env('APP_NAME')}}" name="description" />
    <meta content="{{config('app.name')}}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/drive/logo/arm.png">
    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {!! session()->get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session()->has('error'))

                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-outline me-2"></i>
                        {!! session()->get('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>Login to continue to {{config('app.name')}}.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="/assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="auth-logo">
                            <a href="#" class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="/assets/drive/logo/arm.png" alt="" class="rounded-circle" height="74" width="74">
                                    </span>
                                </div>
                            </a>

                            <a href="{{route('login')}}" class="auth-logo-dark">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="/assets/drive/logo/arm.png" alt="" class="rounded-circle" height="74" width="74">
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="p-2">
                            <form autocomplete="off" method="post" class="form-horizontal" action="{{route('login')}}">
                                {{ csrf_field() }}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="text" class="form-control" name="email" value="{{old('email')}}" id="email" placeholder="Email Address">
                                    @error('email')
                                        <i class="text-danger">{{$message}}</i>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" aria-label="Password" aria-describedby="password-addon">
                                        <button class="btn btn-light " type="button" id="togglePassword"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                    @error('password')
                                    <i class="text-danger">{{$message}}</i>
                                    @enderror
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                    <label class="form-check-label" for="remember-check">
                                        Remember me
                                    </label>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                @include('partials._auth-footer-note')

            </div>
        </div>
    </div>
</div>
<!-- end account-pages -->

<!-- JAVASCRIPT -->
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- App js -->
<script src="/assets/js/app.js"></script>
<script>
    const passwordField = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');

    togglePasswordButton.addEventListener('click', function () {
        // Toggle the password visibility
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
</script>
</body>
</html>

