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
                
                @can('list-category')
                <li>
                    <a href="{{ route('categories.index') }}"
                        class="{{ Route::currentRouteName() == 'categories.index' ? 'active' : '' }}">
                        <i data-feather="grid"></i>
                        <span>Categories</span>
                    </a>
                </li>
                @endcan

                @can('list-subcategory')
                <li>
                    <a href="{{ route('subcategories.index') }}"
                        class="{{ Route::currentRouteName() == 'subcategories.index' ? 'active' : '' }}">
                        <i data-feather="list"></i>
                        <span>Subcategories</span>
                    </a>
                </li>
                @endcan

                @can('list-product')
                <li class="menu-item {{ Route::is('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" class="menu-link">
                        <i data-feather="package"></i>
                        <span>Products</span>
                    </a>
                </li>
                @endcan

                {{-- Stock Management --}}
                @can('list-stock')
                <li>
                    <a href="{{ route('stocks.index') }}"
                        class="{{ Route::is('stocks.*') ? 'active' : '' }}">
                        <i data-feather="database"></i>
                        <span>Stock Management</span>
                    </a>
                </li>
                @endcan

                {{-- Orders Section --}}
                <li class="menu-title mt-2" data-key="t-orders">Orders</li>

                @can('list-online-order')
                @php
                    $pendingStatusId = \App\Models\OrderStatus::where('name', 'Pending')->value('id');
                    $newOrdersCount = $pendingStatusId ? \App\Models\Order::where('order_status_id', $pendingStatusId)->count() : 0;
                @endphp
                <li>
                    <a href="{{ route('orders.online') }}" class="{{ Route::currentRouteName() == 'orders.online' ? 'active' : '' }}">
                        <i data-feather="bell"></i>
                        <span>Online Orders</span>
                        @if($newOrdersCount > 0)
                            <span class="badge rounded-pill bg-danger float-end" style="margin-top: 2px;">{{ $newOrdersCount }}</span>
                        @endif
                    </a>
                </li>
                @endcan

                @canany(['list-sale-order', 'list-returned-order', 'list-canceled-order'])
                <li>
                    <a href="javascript: void(0);" class="has-arrow"
                        aria-expanded="{{ Route::is('orders.sales', 'orders.returned', 'orders.canceled') ? 'true' : 'false' }}">
                        <i data-feather="shopping-cart"></i>
                        <span>Manage Orders</span>
                    </a>
                    <ul class="sub-menu {{ Route::is('orders.sales', 'orders.returned', 'orders.canceled') ? 'show' : '' }}">
                        @can('list-sale-order')
                        <li>
                            <a href="{{ route('orders.sales') }}" class="{{ Route::currentRouteName() == 'orders.sales' ? 'active' : '' }}">Sales</a>
                        </li>
                        @endcan
                        @can('list-returned-order')
                        <li>
                            <a href="{{ route('orders.returned') }}" class="{{ Route::currentRouteName() == 'orders.returned' ? 'active' : '' }}">Returned Orders</a>
                        </li>
                        @endcan
                        @can('list-canceled-order')
                        <li>
                            <a href="{{ route('orders.canceled') }}" class="{{ Route::currentRouteName() == 'orders.canceled' ? 'active' : '' }}">Canceled Orders</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @can('list-order-status')
                <li>
                    <a href="{{ route('order-statuses.index') }}"
                        class="{{ Route::is('order-statuses.*') ? 'active' : '' }}">
                        <i data-feather="loader"></i>
                        <span>Order Statuses</span>
                    </a>
                </li>
                @endcan
            @can('list-brand')
                <li>
                    <a href="{{ route('brands.index') }}"
                        class="{{ Route::currentRouteName() == 'brands.index' ? 'active' : '' }}">
                        <i data-feather="tag"></i>
                        <span>Brands</span>
                    </a>
                </li>
                @endcan

                @can('list-size')
                <li>
                    <a href="{{ route('sizes.index') }}"
                        class="{{ Route::currentRouteName() == 'sizes.index' ? 'active' : '' }}">
                        <i data-feather="maximize"></i>
                        <span>Sizes</span>
                    </a>
                </li>
                @endcan

                @can('list-color')
                <li>
                    <a href="{{ route('colors.index') }}"
                        class="{{ Route::currentRouteName() == 'colors.index' ? 'active' : '' }}">
                        <i data-feather="aperture"></i>
                        <span>Colors</span>
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
                        <span>System Users</span>
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

                {{-- Customers --}}
                @can('list-customer')
                <li>
                    <a href="{{ route('customers.index') }}"
                        class="{{ Route::is('customers.*') ? 'active' : '' }}">
                        <i data-feather="smile"></i>
                        <span>Customers</span>
                    </a>
                </li>
                @endcan

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