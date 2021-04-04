<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('/assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('/assets/images/favicon.png')}}" type="image/x-icon">
    <title>PT Dira Utama Jaya</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
          rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/fontawesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/date-picker.css')}}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{asset('/assets/css/color-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/responsive.css')}}">
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    @yield('head')
    @toastr_css
</head>
<body onload="startTime()">
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row m-0">
            <form class="form-inline search-full" action="#" method="get">
                <div class="form-group w-100">
                    <div class="Typeahead Typeahead--twitterUsers">
                        <div class="u-posRelative">
                            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                                   placeholder="Search Cuba .." name="q" title="" autofocus>
                            <div class="spinner-border Typeahead-spinner" role="status"><span
                                    class="sr-only">Loading...</span></div>
                            <i class="close-search" data-feather="x"></i>
                        </div>
                        <div class="Typeahead-menu"></div>
                    </div>
                </div>
            </form>
            <div class="main-header-left">
                <div class="logo-wrapper"><a href="index.html"><img class="img-fluid"
                                                                    src="../assets/images/logo/logo.png" alt=""></a>
                </div>
                <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid"
                                               id="sidebar-toggle"> </i></div>
            </div>
            <div class="left-menu-header col horizontal-wrapper pl-0">
                <ul class="horizontal-menu">

                </ul>
            </div>
            <div class="nav-right col-8 pull-right right-menu">
                <ul class="nav-menus">
                    <li class="profile-nav onhover-dropdown p-0">
                        <div class="media profile-media"><img style="width: 3em;height:2.7em;border-radius:50%;"
                                                              src="{{asset(auth()->user()->img ? Storage::url('/user/'.auth()->user()->img) : '/assets/images/user/user.png')}}"
                                                              alt="">
                            <div class="media-body"><span>{{auth()->user()->name}}</span>
                                <p class="mb-0 font-roboto">{{auth()->user()->role}}<i
                                        class="middle fa fa-angle-down"></i></p>
                            </div>
                        </div>
                        <ul class="profile-dropdown onhover-show-div">
                            @if (in_array(auth()->user()->role,['admin','head','ketua']))
                                <a href="{{route('user.create')}}">
                                    <li><i data-feather="user"></i><span>Register </span></li>
                                </a>
                            @endif

                            <form action="{{route('logout')}}" method="post" id="log" style="display: inline">
                                @csrf
                                <li onclick="document.getElementById('log').submit()"><i
                                        data-feather="log-in"> </i><span>Log out</span></li>
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
            <script id="result-template" type="text/x-handlebars-template">
                <div class="ProfileCard u-cf">
                    <div class="ProfileCard-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-airplay m-0">
                            <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                            <polygon points="12 15 17 21 7 21 12 15"></polygon>
                        </svg>
                    </div>
                    <div class="ProfileCard-details">
                        <div class="ProfileCard-realName"></div>
                    </div>
                </div>
            </script>
            <script id="empty-template" type="text/x-handlebars-template">
                <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down,
                    yikes!
                </div></script>
        </div>
    </div>
    <!-- Page Header Ends                              -->
    <!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
        <!-- Page Sidebar Start-->
    @include('frontend.layouts.sidebar')
    <!-- Page Sidebar Ends-->
        <div class="page-body">

            <!-- Container-fluid starts-->
            <div class="page-header">
            </div>

            <div class="col-xl-12 col-lg-12  calendar-sec box-col-12">
                <div class="card gradient-primary o-hidden ">
                    <div class="card-body">
                        <div class="card-header card-no-border">
                            <h5>Dibutuhkan aktivitas finger</h5>
                        </div>
                        <div class="card-body new-update pt-0">
                            <div class="activity-timeline">
                                @if(Auth::user()->fingerprints!=null)
                                    <a href="{{ flexcodesdk::getVerificationLink(Auth::id()) }}"
                                       class="btn btn-primary-gradien">Klik disini untuk melaukan fingerprint</a>
                                @else
                                    <a href="{{ flexcodesdk::getRegistrationLink(Auth::id()) }}"
                                       class="btn btn-primary-gradien">Klik disini mendaftarkan sidik jari</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>
<!-- footer start-->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 footer-copyright">
                <p class="mb-0">Copyright 2020 © Cuba All rights reserved.</p>
            </div>
            <div class="col-md-6">
                <p class="pull-right mb-0">Developed with <i class="fa fa-heart font-secondary"></i></p>
            </div>
        </div>
    </div>
</footer>


@jquery
@toastr_js
@toastr_render
<!-- latest jquery-->
<script src="{{asset('/assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{asset('/assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('/assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('/assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('/assets/js/sidebar-menu.js')}}"></script>
<script src="{{asset('/assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
<script src="{{asset('/assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('/assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('/assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{asset('/assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{asset('/assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('/assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('/assets/js/notify/bootstrap-notify.min.js')}}"></script>
{{-- <script src="{{asset('/assets/js/dashboard/default.js')}}"></script> --}}
<script src="{{asset('/assets/js/notify/index.js')}}"></script>
<script src="{{asset('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('/assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('/assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('/assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('/assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('/assets/js/typeahead-search/typeahead-custom.j')}}s"></script>
<script src="{{asset('/assets/js/tooltip-init.js')}}"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>


<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('/assets/js/script.js')}}"></script>
{{-- <script src="{{asset('/assets/js/theme-customizer/customizer.js')}}"></script> --}}
<!-- login js-->
<!-- Plugin used-->
<script>
    $(document).ready(function () {
        function waiting() {
            var action = localStorage.getItem("action");
            if (action != null) {
                window.location = action;
            } else {
                setTimeout(waiting, 3000);
            }
        }
        waiting();
    });
</script>
</body>
</html>
