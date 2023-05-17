<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register</title>
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
        <div id="layoutAuthentication">
            <!-- Layout content-->
            <div id="layoutAuthentication_content">
                <!-- Main page content-->
                <main>
                    <!-- Main content container-->
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xxl-7 col-xl-10">
                                <div class="card card-raised shadow-10 mt-5 mt-xl-10 mb-5">
                                    <div class="card-body p-5">
                                        <!-- Auth header with logo image-->
                                        <div class="text-center">
                                            <img class="mb-3" src="{{ asset('theme/assets/img/icons/background.svg') }}" alt="..." style="height: 48px" />
                                            <h1 class="display-5 mb-6">Create New Account</h1>
                                        </div>
                                        <!-- Register new account form-->
                                        <form method="POST" action="{{ route('register') }}">
                                            @csrf
                                            <div class="mb-4">
                                                <mwc-textfield id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus class="w-100 @error('name') is-invalid @enderror" label="Name" outlined></mwc-textfield>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <mwc-textfield id="email" type="email" class="w-100 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required label="Email Address" autocomplete="email" outlined></mwc-textfield>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="row">
                                                <div class="mb-4 w-50">
                                                    <mwc-textfield id="password" type="password" class="w-100 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" label="Password" outlined icontrailing="visibility_off" type="password"></mwc-textfield>

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-4 w-50">
                                                    <mwc-textfield id="password-confirm" type="password" class="w-100" name="password_confirmation" required autocomplete="new-password" label="Verify Password" outlined icontrailing="visibility_off" type="password"></mwc-textfield>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small fw-500 text-decoration-none" href="{{ route('login') }}">Sign in instead</a>
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Create Account') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
