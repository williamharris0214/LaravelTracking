<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tracking') }}</title>
    <link href="{{ asset('theme/assets/img/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
    <!-- Load Material Icons from Google Fonts-->
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
    <!-- Roboto and Roboto Mono fonts from Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500" rel="stylesheet" />
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('theme/css/styles.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/theme.css">
    <!-- Scripts -->
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->
</head>
<body>
    <div id="app">
        <!-- Top app bar navigation menu-->
        <nav class="top-app-bar navbar navbar-expand navbar-dark bg-dark" style="background-color:#6200EA!important">
            <div class="container-fluid px-4" style="justify-content: flex-end;">
                <!-- Drawer toggle button -->
                <button class="btn btn-lg btn-icon order-1 order-lg-0" id="drawerToggle" href="javascript:void(0);"><i class="material-icons">menu</i></button>
                <!-- Navbar brand -->
                <a class="navbar-brand me-auto" href="/">
                <div class="text-uppercase font-monospace">
                    TRACKING PRO
                    @if(Auth::user()->role === 5)
                        <small style="color: #0FF0FC">Admin</small>
                    @endif
                </div>
                </a>
                <!-- Navbar items -->
                <div class="d-flex align-items-center mx-3 me-lg-0">
                    <!-- Navbar-->
                    <!-- <ul class="navbar-nav d-none d-lg-flex">
                        <li class="nav-item"><a class="nav-link" href="index.html">Overview</a></li>
                        <li class="nav-item"><a class="nav-link" href="https://docs.startbootstrap.com/material-admin-pro" target="_blank">Documentation</a></li>
                    </ul> -->
                    <!-- Navbar buttons-->
                    <div class="d-flex">
                        <!-- Messages dropdown-->
                        <!-- <div class="dropdown dropdown-notifications d-none d-sm-block">
                            <button class="btn btn-lg btn-icon dropdown-toggle me-3" id="dropdownMenuMessages" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">mail_outline</i></button>
                            <ul class="dropdown-menu dropdown-menu-end me-3 mt-3 py-0 overflow-hidden" aria-labelledby="dropdownMenuMessages">
                                <li><h6 class="dropdown-header bg-primary text-white fw-500 py-3">Messages</h6></li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item unread" href="#!">
                                        <div class="dropdown-item-content">
                                            <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">Hi there, I had a question about something, is there any way you can help me out?</div></div>
                                            <div class="dropdown-item-content-subtext">Mar 12, 2021 &middot; Juan Babin</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item" href="#!">
                                        <div class="dropdown-item-content">
                                            <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">Thanks for the assistance the other day, I wanted to follow up with you just to make sure everyting is settled.</div></div>
                                            <div class="dropdown-item-content-subtext">Mar 10, 2021 &middot; Christine Hendersen</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item" href="#!">
                                        <div class="dropdown-item-content">
                                            <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">Welcome to our group! It is good to see new members and I know you will do great!</div></div>
                                            <div class="dropdown-item-content-subtext">Mar 8, 2021 &middot; Celia J. Knight</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item py-3" href="#!">
                                        <div class="d-flex align-items-center w-100 justify-content-end text-primary">
                                            <div class="fst-button small">View all</div>
                                            <i class="material-icons icon-sm ms-1">chevron_right</i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                        <!-- Notifications and alerts dropdown-->
                        <!-- <div class="dropdown dropdown-notifications d-none d-sm-block">
                            <button class="btn btn-lg btn-icon dropdown-toggle me-3" id="dropdownMenuNotifications" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">notifications</i></button>
                            <ul class="dropdown-menu dropdown-menu-end me-3 mt-3 py-0 overflow-hidden" aria-labelledby="dropdownMenuNotifications">
                                <li><h6 class="dropdown-header bg-primary text-white fw-500 py-3">Alerts</h6></li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item unread" href="#!">
                                        <i class="material-icons leading-icon">assessment</i>
                                        <div class="dropdown-item-content me-2">
                                            <div class="dropdown-item-content-text">Your March performance report is ready to view.</div>
                                            <div class="dropdown-item-content-subtext">Mar 12, 2021 &middot; Performance</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item" href="#!">
                                        <i class="material-icons leading-icon">check_circle</i>
                                        <div class="dropdown-item-content me-2">
                                            <div class="dropdown-item-content-text">Tracking codes successfully updated.</div>
                                            <div class="dropdown-item-content-subtext">Mar 12, 2021 &middot; Coverage</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item" href="#!">
                                        <i class="material-icons leading-icon">warning</i>
                                        <div class="dropdown-item-content me-2">
                                            <div class="dropdown-item-content-text">Tracking codes have changed and require manual action.</div>
                                            <div class="dropdown-item-content-subtext">Mar 8, 2021 &middot; Coverage</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-0" /></li>
                                <li>
                                    <a class="dropdown-item py-3" href="#!">
                                        <div class="d-flex align-items-center w-100 justify-content-end text-primary">
                                            <div class="fst-button small">View all</div>
                                            <i class="material-icons icon-sm ms-1">chevron_right</i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                        <!-- User profile dropdown-->
                        <div class="dropdown">
                            <button class="btn btn-lg btn-icon dropdown-toggle" id="dropdownMenuProfile" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">person</i></button>
                            <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="dropdownMenuProfile">
                                <!-- <li>
                                    <a class="dropdown-item" href="#!">
                                        <i class="material-icons leading-icon">person</i>
                                        <div class="me-3">Profile</div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#!">
                                        <i class="material-icons leading-icon">settings</i>
                                        <div class="me-3">Settings</div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#!">
                                        <i class="material-icons leading-icon">help</i>
                                        <div class="me-3">Help</div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" /></li> -->
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="material-icons leading-icon">logout</i>
                                        <div class="me-3">{{ __('Logout') }}</div>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Layout wrapper-->
        <div id="layoutDrawer">
            <!-- Layout navigation-->
            <div id="layoutDrawer_nav">
                <!-- Drawer navigation-->
                <nav class="drawer accordion drawer-light bg-white" id="drawerAccordion">
                    <div class="drawer-menu">
                        <div class="nav">
                            <!-- Drawer link (Dashboard)-->
                            @if(Auth::user()->role === 5)
                            <!-- <a class="nav-link" href="/" style="margin-top:30px;">
                                <div class="nav-link-icon"><i class="material-icons">dashboard</i></div>
                                DashBoard
                            </a> -->
                            <a class="nav-link" href="/user_manage" style="margin-top:10px;">
                                <div class="nav-link-icon"><i class="material-icons">build</i></div>
                                User Management
                            </a>
                            @endif
                            <a class="nav-link" href="/tracking" style="margin-top:10px;">
                                <div class="nav-link-icon"><i class="material-icons">language</i></div>
                                Tracking Data
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutDrawer_content">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Load global scripts-->
    <script type="module" src="{{ asset('theme/js/material.js') }}"></script>
    <script src="{{ asset('theme/js/scripts.js') }}"></script>
    <!--  Load Chart.js via CDN-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0-beta.10/chart.min.js" crossorigin="anonymous"></script>
    <!--  Load Chart.js customized defaults-->
    <script src="{{ asset('theme/js/charts/chart-defaults.js') }}"></script>
    <!-- Load Simple DataTables Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{ asset('theme/js/datatables/datatables-simple-demo.js') }}"></script>
    <!-- Load the latest jQuery -->
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('theme/js/litepicker.js') }}"></script>
    <script src="{{ asset('theme/js/prism.js') }}"></script>
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        @if(Auth::user()->role !== 5)
            //document.body.classList.toggle('drawer-toggled');
            // const drawer_nav = document.getElementById('layoutDrawer_nav');
            // drawer_nav.style.transform = 'translateX(-279px)';
            if (window.innerWidth >= 1200) {
                document.body.classList.toggle("drawer-toggled");
            }
        @endif
    </script>
    @stack('script')
</body>
</html>
