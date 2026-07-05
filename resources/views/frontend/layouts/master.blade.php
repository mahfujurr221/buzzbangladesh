<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Buzz - Fashion for Everyone</title>
        <link rel="shortcut icon" href="{{ asset('frontend/images/fav.png') }}" type="image/x-icon" />
        <link rel="stylesheet" href="{{ asset('frontend/css/icomoon/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/output-scss.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/output-tailwind.css') }}" />
    </head>

    <body>
        @include('frontend.layouts.includes.top-nav')

        @include('frontend.layouts.includes.header')

        @yield('content')

        @include('frontend.layouts.includes.footer')

        <script src="{{ asset('frontend/js/phosphor-icons.js') }}"></script>
        <script src="{{ asset('frontend/js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('frontend/js/main.js') }}"></script>
    </body>

</html>
