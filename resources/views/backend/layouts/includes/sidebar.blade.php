<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- CMS Section --}}
                <li class="menu-title mt-2" data-key="t-components">CMS</li>
                
                @can('list-brand')
                <li>
                    <a href="{{ route('brands.index') }}"
                        class="{{ Route::currentRouteName() == 'brands.index' ? 'active' : '' }}">
                        <i data-feather="tag"></i>
                        <span>Brands</span>
                    </a>
                </li>
                @endcan

                <li class="menu-title mt-2" data-key="t-components">Users and Roles</li>
                @can('list-role')
                <li>
                    <a href="{{ route('permissions.index') }}"
                        class="{{ Route::currentRouteName() == 'permissions.index' ? 'active' : '' }}">
                        <i data-feather="shield"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                @endcan
                {{-- @endif --}}

                @can('list-role')
                <li>
                    <a href="{{ route('roles.index') }}"
                        class="{{ Route::currentRouteName() == 'roles.index' || Route::currentRouteName() == 'role.permissions' ? 'active' : '' }}">
                        <i data-feather="user-check"></i>
                        <span>Roles</span>
                    </a>
                </li>
                @endcan

                {{-- Users Nav --}}
                @canany(['list-user', 'create-user'])
                <li>
                    <a href="javascript: void(0);" class="has-arrow"
                        aria-expanded="{{ Route::is('users.*') ? 'true' : 'false' }}">
                        <i data-feather="users"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu {{ Route::is('users.*') ? 'show' : '' }}">
                        @can('create-user')
                        <li>
                            <a href="{{ route('users.create') }}"
                                class="{{ Route::currentRouteName() == 'users.create' ? 'active' : '' }}">
                                Add User
                            </a>
                        </li>
                        @endcan
                        @can('list-user')
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="{{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}">
                                Users List
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Settings --}}
                @can('update-setting')
                <li class="menu-title mt-2 text-secondary">Settings</li>
                <li>
                    <a href="{{ route('settings.index') }}"
                        class="{{ Route::currentRouteName() == 'settings.index' ? 'active' : '' }}">
                        <i data-feather="settings"></i>
                        <span>Setting</span>
                    </a>
                </li>
                @endcan

            </ul>
        </div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->