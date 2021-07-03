<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>HappySelling.id</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Ico Font CSS -->
    <link rel="stylesheet" href="{{ asset('public/comingsoon/css/icofont.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('public/comingsoon/css/bootstrap.min.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('public/comingsoon/css/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('public/comingsoon/css/responsive.css') }}">

</head>
<body class="bg-img contact-body-bg">
    <div class="canvas-area">
        <canvas class="constellation"></canvas>
    </div>
    <!-- Preloader Starts -->
    <div class="preloader-wrap">
        <div class="preloader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!--/Preloader Ends -->

    <!-- MAIN CONTENT PART START -->
    <div class="bg-img color-white main-container">
        <!-- HEADER PART START-->
        <div id="header">
            <div class="container bg-header-dark">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="#">
                        <img src="img/logo-icon.png" alt="">
                        <h3>HappySelling.id</h3>
                    </a>
                </nav>
            </div>
        </div>
        <!-- HEADER PART END-->

        <!-- BODY CONTENT PART START -->
        <div id="main-content-home-style2" class="xs-no-positioning fixed fixed-middle">
            <!-- TITLE PART START -->
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="title text-left">
                            <span class="home2-small-title">HappySelling.id</span>
                            <span class="home2-main-title">Anda tidak memiliki hak akses </span>
                        </div>
                    </div>

                    <div class="col-md-6 mt-5">
                        <div class="subscribe-form pb-3">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                              <button type="button" class="btn btn-orange btn-round">LOGOUT</button>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- TITLE PART END -->
            <!-- SOCIAL ICONS END-->
        </div>
        <!-- BODY CONTENT PART END -->
    </div>
    <!-- /MAIN CONTENT PART ENDS -->

    <!-- jQuery -->
    <script src="{{ asset('public/comingsoon/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('public/comingsoon/js/bootstrap.min.js') }}"></script>
    <!-- Countdown Timer -->
    <script src="{{ asset('public/comingsoon/js/kinetic.js') }}"></script>
    <script src="{{ asset('public/comingsoon/js/jquery.final-countdown.js') }}"></script>
    <!-- zepto JS -->
    <script src="{{ asset('public/comingsoon/js/zepto.min.js') }}"></script>
    <!-- constellation JS -->
    <script src="{{ asset('public/comingsoon/js/constellation.min.js') }}"></script>
    <!-- stars JS -->
    <script src="{{ asset('public/comingsoon/js/stars.js') }}"></script>
    <!-- scripts -->
    <script src="{{ asset('public/comingsoon/js/scripts.js') }}"></script>

</body>
</html>
