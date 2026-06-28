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

    @stack('css')
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

    <!-- back-to-top-start  -->
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="far fa-angle-double-up"></i>
    </button>
    <!-- back-to-top-end  -->

    <!-- search popup start -->
    <div class="search__popup">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="search__wrapper">
                        <div class="search__top d-flex justify-content-between align-items-center">
                            <div class="search__logo" style="width:15%;">
                                <a href="{{ route('frontend.home') }}">
                                    <img src="{{ asset('frontend/assets/img/logo/demo-logo.png') }}" alt="" style="max-height: 40px;">
                                </a>
                            </div>
                            <div class="search__close">
                                <button type="button" class="search__close-btn search-close-btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="search__form">
                            <form action="#">
                                <div class="search__input">
                                    <input class="search-input-field" type="text" placeholder="Type here to search...">
                                    <span class="search-focus-border"></span>
                                    <button type="submit">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- search popup end -->

    @include('frontend.layouts.includes.header')

    @include('frontend.layouts.includes.offcanvas')

    <div id="smooth-wrapper">
        <div id="smooth-content">

            <main class="fix">
                @yield('content')
            </main>

            @include('frontend.layouts.includes.footer')

        </div>
    </div>


    <!-- JS here -->
    <script src="{{ asset('frontend/assets/js/jquery.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/waypoints.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/magnific-popup.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/counterup.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/swiper-bundle.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/meanmenu.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/tilt.jquery.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope-pkgd.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/gsap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ScrollSmoother.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/split-text.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>