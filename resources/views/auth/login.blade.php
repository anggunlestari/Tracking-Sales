<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../../../public">
    <meta charset="utf-8" />
    <title>Login | Tracking Sales</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ url('form_login/images/icons/cazh.png') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/vendor/bootstrap/css/bootstrap.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ url('form_login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ url('form_login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/vendor/animate/animate.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/vendor/css-hamburgers/hamburgers.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/vendor/animsition/css/animsition.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/vendor/select2/select2.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ url('form_login/vendor/daterangepicker/daterangepicker.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/css/util.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('form_login/css/main.css') }}" />
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100" style="background-image: url('form_login/images/pexels_2.jpg');">
            {{-- <div class="container-login100" style="background:#eca1a6"> --}}
            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-20">
                    <img alt="Logo" src="{{ url('form_login/images/logocazh.png') }}" height="50" />
                    <h4 class="p-t-20">Sistem Monitoring Aktivitas Sales</h4>
                </span>
                <div>

                    <div>
                        @if (Request::session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong></strong> Username atau password yang dimasukan salah!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('login') }}"
                        class="login100-form validate-form p-b-33 p-t-5">
                        @csrf
                        <div class="wrap-input100 validate-input" data-validate="Enter username">
                            <input class="input100 @error('email') is-invalid @enderror" type="emai" id="email"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="masukan email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Enter password">
                            <input class="input100  @error('password') is-invalid @enderror" type="password"
                                id="password" name="password" required autocomplete="current-password"
                                placeholder="masukan password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                        </div>

                        <div class="container-login100-form-btn m-t-32">
                            <button type="submit" class="login100-form-btn">
                                Masuk
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>


        <div id="dropDownSelect1"></div>

        <!--===============================================================================================-->
        <script src="{{ url('form_login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
        <!--===============================================================================================-->
        <script src="{{ url('form_login/vendor/animsition/js/animsition.min.js') }}"></script>
        <!--===============================================================================================-->
        <script src="{{ url('form_login/vendor/bootstrap/js/popper.js') }}"></script>
        <script src="{{ url('form_login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <!--===============================================================================================-->
        <script src="{{ url('form_login/vendor/select2/select2.min.js') }}"></script>
        <!--===============================================================================================-->
        <script src="{{ url('form_login/vendor/daterangepicker/moment.min.js') }}"></script>
        <script src="{{ url('form_login/vendor/daterangepicker/daterangepicker.js') }}"></script>
        <!--===============================================================================================-->
        <script src="{{ url('form_login/vendor/countdowntime/countdowntime.js') }}"></script>
        <!--===============================================================================================-->
        <script src="{{ url('form_login/js/main.js') }}"></script>

</body>

</html>
