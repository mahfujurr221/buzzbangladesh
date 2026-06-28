<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark py-4">
                    <span class="logo-sm">
                        <img src="{{ asset(setting()->logo && file_exists(public_path('uploads/' . setting()->logo)) ? 'uploads/' . setting()->logo : 'frontend/assets/img/logo/demo-logo.png') }}" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset(setting()->logo && file_exists(public_path('uploads/' . setting()->logo)) ? 'uploads/' . setting()->logo : 'frontend/assets/img/logo/demo-logo.png') }}" alt="" height="40"> 
                        <span class="logo-txt text-uppercase fw-bold fs-4">{{ setting()->site_name }}</span>
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset(setting()->logo && file_exists(public_path('uploads/' . setting()->logo)) ? 'uploads/' . setting()->logo : 'frontend/assets/img/logo/demo-logo.png') }}" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset(setting()->logo && file_exists(public_path('uploads/' . setting()->logo)) ? 'uploads/' . setting()->logo : 'frontend/assets/img/logo/demo-logo.png') }}" alt="" height="40">
                        <span class="logo-txt text-uppercase fw-bold fs-6">{{ setting()->site_name }}</span>
                    </span>
                </a>
            </div>

            <button type="button" class="px-3 btn btn-sm font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-primary" type="button"><i class="align-middle bx bx-search-alt"></i></button>
                </div>
            </form>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="p-0 dropdown-menu dropdown-menu-lg dropdown-menu-end"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="m-0 form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item right-bar-toggle me-2">
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ asset('backend/images/users/'. (auth()->user()?->image ?? 'default.png')) }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name??'' }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- Profile -->
                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                        <i class="align-middle mdi mdi-face-man font-size-16 me-1"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>

                    <!-- Logout -->
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="align-middle mdi mdi-logout font-size-16 me-1"></i> Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>


        </div>
    </div>
</header>