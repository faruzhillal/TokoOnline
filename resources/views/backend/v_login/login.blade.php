<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon.png') }}">
    <title>Dashboard Dapur Qinzy</title>
    <!-- Custom CSS -->
    <link href="{{ asset('backend/dist/css/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <!-- Preloader -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <!-- Login Box -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-gray-100">
            <div class="auth-box border-top border-secondary shadow-lg">
                <div id="loginform">
                    <div class="text-center p-t-20 p-b-20">
                        <span class="db">
                            <img src="{{ asset('image/berandaIcon.png') }}" style="width: 280px;" alt="logo" />
                        </span>
                    </div>
                    <!-- Error Message -->
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <strong>{{ session('error') }}</strong>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form class="form-horizontal m-t-20" id="loginform" action="{{ route('backend.login') }}"
                        method="post">
                        @csrf
                        <div class="row p-b-30">
                            <div class="col-12">
                                <!-- Email Input -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i
                                                class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="email" value="{{ old('email') }}"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="Masukkan Email" aria-label="Email" aria-describedby="basic-addon1">
                                    @error('email')
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password Input -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2"><i
                                                class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Masukkan Password" aria-label="Password"
                                        aria-describedby="basic-addon2">
                                    @error('password')
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="p-t-20">
                                        <button class="btn btn-info" id="to-recover" type="button"><i
                                                class="fa fa-lock m-r-5"></i> Lost password?</button>
                                        <button class="btn btn-success float-right" type="submit">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Recover Form -->
                <div id="recoverform" style="display: none;">
                    <div class="text-center">
                        <span class="text-white">Enter your e-mail address below and we will send you instructions on
                            how to recover a password.</span>
                    </div>
                    <div class="row m-t-20">
                        <form class="col-12" action="index.html">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white" id="basic-addon1"><i
                                            class="ti-email"></i></span>
                                </div>
                                <input type="email" class="form-control form-control-lg" placeholder="Email Address"
                                    aria-label="Email Address" aria-describedby="basic-addon1">
                            </div>
                            <div class="row m-t-20 p-t-20 border-top border-secondary">
                                <div class="col-12">
                                    <a class="btn btn-success" href="#" id="to-login">Back To Login</a>
                                    <button class="btn btn-info float-right" type="button">Recover</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Required JS -->
    <script src="{{ asset('backend/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script>
        // Tooltip
        $('[data-toggle="tooltip"]').tooltip();
        $(".preloader").fadeOut();

        // Login and Recover Password
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        $('#to-login').click(function() {
            $("#recoverform").hide();
            $("#loginform").fadeIn();
        });
    </script>
</body>

</html>