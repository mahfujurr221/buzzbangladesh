<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta content="Admin Dashboard" name="description" />
    <meta content="BUZZ" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon"
        href="{{ asset(setting()->favicon ? 'backend/images/' . setting()->favicon : 'backend/images/default_favicon.png') }}">

    <!-- Plugin CSS -->

    <link href="{{ asset('backend/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons CSS -->
    <link href="{{ asset('backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('backend/css/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/select2/bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/summernote/summernote-bs5.min.css') }}">
    <!-- App CSS -->
    <link href="{{ asset('backend/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Preloader CSS -->
    <link href="{{ asset('backend/css/custom-preloader.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Theme CSS -->
    <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <script shadow>
        (function() {
            const savedTheme = localStorage.getItem('minia-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            document.documentElement.setAttribute('data-topbar', savedTheme);
            document.documentElement.setAttribute('data-sidebar', savedTheme);
        })();
    </script>

    @stack('css')
</head>

<body>
    <div id="preloader">
        <div class="loader-content">
            <img src="{{ setting()->logo && file_exists(public_path('backend/images/' . setting()->logo)) ? asset('backend/images/' . setting()->logo) : 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%2250%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%22200%22%20height%3D%2250%22%20fill%3D%22%23dddddd%22%2F%3E%3Ctext%20x%3D%22100%22%20y%3D%2225%22%20font-family%3D%22sans-serif%22%20font-size%3D%2220%22%20fill%3D%22%23888888%22%20text-anchor%3D%22middle%22%20alignment-baseline%3D%22middle%22%3ELogo%3C%2Ftext%3E%3C%2Fsvg%3E' }}" alt="{{ setting()->site_name }}" class="loader-logo">
        </div>
    </div>

    <div id="layout-wrapper">

        @include('backend.layouts.includes.header')
        @include('backend.layouts.includes.sidebar')

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    @include('backend.layouts.includes.page-title')

                    @yield('content')

                </div>
            </div>

            @include('backend.layouts.includes.footer')

        </div>
    </div>

    @include('backend.layouts.includes.right-sidebar')

    @if(session('message'))
    <div class="top-0 p-3 position-fixed end-0" style="z-index: 1080">
        <div class="toast align-items-center border-0 show" role="alert" aria-live="assertive" aria-atomic="true"
            style="background-color: {{ session('message.type') === 'success' ? '#16a34a' : (session('message.type') === 'danger' ? '#dc2626' : (session('message.type') === 'warning' ? '#f59e0b' : '#3b82f6')) }}; color: white;">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('message.text') }}
                </div>
                <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.querySelector('.toast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                toast.show();
            }
        });
    </script>
    @endif


    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/libs/feather-icons/feather.min.js') }}"></script>

    <!-- ApexCharts -->
    <script src="{{ asset('backend/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Dashboard Init -->
    {{-- <script src="{{ asset('backend/js/pages/dashboard.init.js') }}"></script> --}}
    <script src="{{ asset('backend/js/summernote/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('backend/js/select2/select2.full.min.js') }}"></script>
    <!-- App JS -->
    <script src="{{ asset('backend/js/app.js') }}"></script>

    <script>
        window.addEventListener('load', function() {
            hidePreloader();
        });

        // Maximum loader run time: 3 seconds
        setTimeout(function() {
            hidePreloader();
        }, 3000);

        function hidePreloader() {
            const preloader = document.getElementById('preloader');
            if (preloader && !preloader.classList.contains('fade-out')) {
                preloader.classList.add('fade-out');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }
        }

        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>
    <script>
        $('.summernote').summernote({
            placeholder: 'Write your content here...',
            tabsize: 2,
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>

    @stack('scripts')
</body>

</html>