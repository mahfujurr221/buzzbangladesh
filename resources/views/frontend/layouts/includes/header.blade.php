<div id="header" class="relative w-full z-50">
    <div class="header-menu style-one relative w-full md:h-[74px] h-[56px] bg-linear">
        <div class="container mx-auto h-full">
            <div class="header-main flex justify-between items-center h-full">
                <div class="menu-mobile-icon lg:hidden flex items-center">
                    <i class="icon-category text-2xl"></i>
                </div>
                <div class="logo flex items-center flex-1">
                    <a href="{{ route('frontend.home') }}" class="flex items-center max-lg:absolute max-lg:left-1/2 max-lg:-translate-x-1/2">
                        @if($setting?->logo)
                            <img src="{{ asset('frontend/assets/images/' . $setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" class="h-8 md:h-10 lg:h-11 w-auto max-w-[130px] md:max-w-[180px] object-contain">
                        @else
                            <div class="heading4">{{ $setting?->site_name ?? 'Buzz' }}</div>
                        @endif
                    </a>
                </div>
                <div class="menu-main h-full max-lg:hidden flex-none">
                    <ul class="flex items-center gap-6 xl:gap-8 h-full">
                        @foreach($categories as $category)
                            <li class="h-full relative">
                                <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->get('category') === $category->slug ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                        @if(!empty($hasActiveDeals))
                        <li class="h-full relative">
                            <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}"
                               class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 font-bold {{ request()->get('filter') === 'hot-deals' ? 'active' : '' }}"
                               style="color: #9A0002;">
                                🔥 Hot Deals
                            </a>
                        </li>
                        @endif
                        <li class="h-full relative">
                            <a href="{{ url('about-us') }}" class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->is('about-us') ? 'active' : '' }}"> About Us </a>
                        </li>
                        <li class="h-full relative">
                            <a href="{{ url('contact-us') }}" class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->is('contact-us') ? 'active' : '' }}"> Contact Us </a>
                        </li>
                        <li class="h-full relative">
                            <a href="{{ route('frontend.track.order') }}" class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.track.order') ? 'active' : '' }}"> Track Order </a>
                        </li>
                    </ul>
                </div>

                <div class="right flex items-center justify-end gap-4 flex-1">
                    <a href="javascript:void(0)" class="search-icon cursor-pointer">
                        <i class="ph ph-magnifying-glass text-2xl"></i>
                    </a>
                    
                    <!-- Auth Buttons Desktop -->
                    <div class="hidden lg:flex items-center gap-2 border-l border-gray-200 pl-4">
                        @auth
                            <style>
                                .profile-dropdown-menu {
                                    display: none;
                                    position: absolute;
                                    top: 100%;
                                    right: 0;
                                    width: 150px;
                                    background-color: white;
                                    border: 1px solid #f3f4f6;
                                    border-radius: 0.75rem;
                                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                                    z-index: 50;
                                }
                                .profile-dropdown-wrapper:hover .profile-dropdown-menu {
                                    display: block;
                                }
                            </style>
                            <div class="relative profile-dropdown-wrapper" style="padding-bottom: 15px; margin-bottom: -15px;">
                                <a href="{{ route('frontend.customer.dashboard') }}" class="text-sm font-semibold flex items-center gap-1 transition-colors" style="color: #9A0002;">
                                    <i class="ph ph-user-circle text-lg"></i> My Profile
                                </a>
                                <div class="profile-dropdown-menu">
                                    <a href="{{ route('frontend.customer.dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#9A0002] transition-colors rounded-t-xl" style="border-bottom: 1px solid #eee;">Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}" class="block m-0">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors rounded-b-xl" style="background:none; border:none; cursor:pointer;">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-black transition-colors">Login</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-gray-700 hover:text-black transition-colors">Register</a>
                        @endauth
                    </div>
                    
                    <!-- Auth Icon Mobile -->
                    @auth
                        <a href="{{ route('frontend.customer.dashboard') }}" class="lg:hidden text-2xl" style="color: #9A0002;">
                            <i class="ph ph-user-circle"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="lg:hidden text-2xl text-gray-800">
                            <i class="ph ph-user"></i>
                        </a>
                    @endauth

                    <div class="open-cart-modal cursor-pointer border-l border-gray-200 pl-4 max-md:border-none max-md:pl-0">
                        <div class="cart-icon relative">
                            <i class="ph ph-handbag text-2xl"></i>
                            <span class="quantity cart-quantity absolute -right-1.5 -top-1.5 text-xs text-white w-4 h-4 flex items-center justify-center rounded-full" style="background-color: #9A0002;">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div id="menu-mobile" class="">
        <div class="menu-container bg-white h-full">
            <div class="container h-full">
                <div class="menu-main h-full overflow-hidden">
                    <div class="heading py-3 px-4 relative flex items-center justify-between border-b" style="border-color: #f3f4f6;">
                        <a href="{{ route('frontend.home') }}" class="logo block">
                            @if($setting?->logo)
                                <img src="{{ asset('frontend/assets/images/' . $setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" class="h-8 w-auto max-w-[130px] object-contain">
                            @else
                                <div class="text-2xl font-bold">{{ $setting?->site_name ?? 'Buzz' }}</div>
                            @endif
                        </a>
                        <div class="close-menu-mobile-btn w-8 h-8 rounded-full flex items-center justify-center cursor-pointer" style="background-color: #f3f4f6;">
                            <i class="ph ph-x text-lg"></i>
                        </div>
                    </div>
                    <div class="list-nav mt-4">
                        <ul class="px-4">
                            @foreach($categories as $category)
                                <li style="border-bottom: 1px solid #f3f4f6; padding-bottom: 12px; margin-bottom: 12px;">
                                    <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="text-lg font-semibold flex items-center" style="{{ request()->get('category') === $category->slug ? 'color: #9A0002;' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                            <li style="border-top: 1px solid #f3f4f6; padding-top: 12px; margin-top: 12px;">
                                <a href="{{ url('about-us') }}" class="text-lg font-semibold flex items-center" style="{{ request()->is('about-us') ? 'color: #9A0002;' : '' }}">About Us</a>
                            </li>
                            <li style="border-bottom: 1px solid #f3f4f6; padding-bottom: 12px; margin-bottom: 12px; margin-top: 16px;">
                                <a href="{{ url('contact-us') }}" class="text-lg font-semibold flex items-center" style="{{ request()->is('contact-us') ? 'color: #9A0002;' : '' }}">Contact Us</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.track.order') }}" class="text-lg font-semibold flex items-center mt-2" style="{{ request()->routeIs('frontend.track.order') ? 'color: #9A0002;' : '' }}">
                                    <span class="flex items-center gap-2"><i class="ph ph-truck text-xl"></i> Track Order</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
