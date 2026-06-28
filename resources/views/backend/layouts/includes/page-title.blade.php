{{-- @php
$routeName = Route::currentRouteName() ?? 'dashboard';
$routeParts = explode('.', $routeName);
$pageTitle = ucfirst($routeParts[0] ?? 'Dashboard');
$breadcrumb = ucfirst(str_replace('.', ' > ', $routeName));
@endphp

<div class="pagetitle">
    <h1>{{ $pageTitle }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item active">{{ $breadcrumb }}</li>
        </ol>
    </nav>
</div> --}}