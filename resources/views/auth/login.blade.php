<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login</title>
        <!-- Load Favicon-->
        <link href="{{ asset('theme/assets/img/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
        <!-- Load Material Icons from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
        <!-- Roboto and Roboto Mono fonts from Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500" rel="stylesheet" />
        <!-- Load main stylesheet-->
        <link href="{{ asset('theme/css/styles.css') }}" rel="stylesheet" />
    </head>
    <body class="bg-primary">
        <!-- Layout wrapper-->
        <div id="layoutAuthentication">
            <!-- Layout content-->
            <div id="layoutAuthentication_content">
                <!-- Main page content-->
                <main>
                    <!-- Main content container-->
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
                                <div class="card card-raised shadow-10 mt-5 mt-xl-10 mb-4">
                                    <div class="card-body p-5">
                                        <!-- Auth header with logo image-->
                                        <div class="text-center">
                                            <img class="mb-3" src="{{ asset('theme/assets/img/icons/background.svg') }}" alt="..." style="height: 48px" />
                                            <h1 class="display-5 mb-6">Login</h1>
                                        </div>
                                        <!-- Login submission form-->
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="mb-4">
                                                <mwc-textfield id="email" type="email" class="w-100 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus label="Email" outlined></mwc-textfield>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <mwc-textfield id="password" type="password" class="w-100 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" label="Password" outlined icontrailing="visibility_off"></mwc-textfield>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <!-- <mwc-formfield label="Remember me">
                                                    <mwc-checkbox name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}></mwc-checkbox>
                                                </mwc-formfield> -->
                                                <div></div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                @if (Route::has('password.request'))
                                                    <!-- <a class="small fw-500 text-decoration-none" href="{{ route('password.request') }}">Forgot Password?</a> -->
                                                    <div></div>
                                                @endif
                                                <button class="btn btn-primary" type="submit">{{ __('Login') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Auth card message-->
                                <div class="text-center mb-5"><a class="small fw-500 text-decoration-none link-white" href="{{ route('register') }}">Need an account? Sign up!</a></div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- Load Bootstrap JS bundle-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- Load global scripts-->
        <script type="module" src="{{ asset('theme/js/material.js') }}"></script>
        <script src="{{ asset('theme/js/scripts.js') }}"></script>
    </body>
</html>
