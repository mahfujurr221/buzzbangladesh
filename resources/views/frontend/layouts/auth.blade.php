<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Sunno International Ltd')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/img/favicon.png') }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom-animation.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
</head>

<body>

    <!-- preloader -->
    <div id="preloader">
        <div class="preloader" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; width: 100vw; margin: 0; left: 0; top: 0;">
            <div class="mb-3">
                <img src="{{ asset(setting()->logo && file_exists(public_path('uploads/' . setting()->logo)) ? 'uploads/' . setting()->logo : 'frontend/assets/img/logo/demo-logo.png') }}" 
                     alt="{{ setting()->site_name }}" 
                     style="max-width: 150px; height: auto;">
            </div>
            <div style="position: relative; width: 50px; height: 50px;">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- preloader end  -->

    <header>
        <!-- tp-header-area-start -->
        <div class="header-signin-area header-bottom__transparent header-signin-ptb z-index-5">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="header-signin-logo">
                            <a href="{{ route('frontend.home') }}"><img src="{{ asset('frontend/assets/img/logo/demo-logo.png') }}"
                                    alt="" style="max-height: 40px;"></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="header-signin-bar text-end tp-menu-bar">
                            <button>
                                <i>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @include('frontend.layouts.includes.offcanvas')

    <div id="smooth-wrapper">
        <div id="smooth-content">
            @yield('content')
        </div>
    </div>

    <!-- JS here -->
    <script src="{{ asset('frontend/assets/js/jquery.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/waypoints.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/magnific-popup.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/counterup.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/swiper-bundle.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/meanmenu.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/tilt.jquery.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope-pkgd.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/smooth-scrollbar.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/gsap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ScrollSmoother.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/split-text.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/parallax-scroll.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

</body>

</html>
