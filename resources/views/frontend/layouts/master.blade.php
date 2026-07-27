<!DOCTYPE html>
<html lang="en">
<head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Buzz - Fashion for Everyone</title>
        <link rel="shortcut icon" href="{{ asset(setting()->favicon ? 'backend/images/' . setting()->favicon : 'backend/images/default_favicon.png') }}" type="image/x-icon" />
        <link rel="stylesheet" href="{{ asset('frontend/css/icomoon/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/output-scss.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/output-tailwind.css') }}" />
        @stack('styles')
    </head>

    <body>
        @include('frontend.layouts.includes.top-nav')

        @include('frontend.layouts.includes.header')

        @yield('content')

        @include('frontend.layouts.includes.footer')

        @include('frontend.layouts.includes.modals')

        @include('frontend.partials.quick-add-modal')

        <script src="{{ asset('frontend/js/phosphor-icons.js') }}"></script>
        <script src="{{ asset('frontend/js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('frontend/js/main.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('frontend/js/cart.js') }}?v={{ time() }}"></script>
        @stack('scripts')
    </body>

</html>

