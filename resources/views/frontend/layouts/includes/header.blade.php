<div id="header" class="relative w-full">
    <div class="header-menu style-one absolute top-0 left-0 right-0 w-full md:h-[74px] h-[56px] bg-transparent">
        <div class="container mx-auto h-full">
            <div class="header-main flex justify-between h-full">
                <div class="menu-mobile-icon lg:hidden flex items-center">
                    <i class="icon-category text-2xl"></i>
                </div>
                <div class="left flex items-center gap-16">
                    <a href="{{ route('frontend.home') }}" class="flex items-center max-lg:absolute max-lg:left-1/2 max-lg:-translate-x-1/2">
                        @if($setting?->logo)
                            <img src="{{ asset('frontend/assets/images/' . $setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" style="max-width: 200px; height: 50px; object-fit: contain;">
                        @else
                            <div class="heading4">{{ $setting?->site_name ?? 'Buzz' }}</div>
                        @endif
                    </a>
                    <div class="menu-main h-full max-lg:hidden">
                        <ul class="flex items-center gap-8 h-full">
                            <li class="h-full relative">
                                <a href="{{ route('frontend.home') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.home') ? 'active' : '' }}"> Home </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ route('frontend.shop') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.shop') ? 'active' : '' }}"> Shop </a>
                            </li>
                            <li class="h-full relative group">
                                <a href="#" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1"> 
                                    Categories <i class="ph ph-caret-down text-xs"></i>
                                </a>
                                <div class="sub-menu absolute top-[calc(100%+15px)] left-0 bg-white shadow-[0_15px_40px_-15px_rgba(0,0,0,0.15)] rounded-xl min-w-[240px] opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:top-full transition-all duration-300 z-50 border border-gray-100 py-3">
                                    <ul class="flex flex-col">
                                        @if(!empty($hasActiveDeals))
                                            <li class="px-3 py-0.5 mb-1 border-b border-gray-50 pb-2">
                                                <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}" class="block px-4 py-2.5 rounded-lg hover:bg-red-50 transition-all duration-300 font-bold flex items-center justify-between group/link" style="color: #9A0002;">
                                                    <span>🔥 Hot Deals</span>
                                                    <i class="ph ph-caret-right text-xs opacity-0 -translate-x-2 group-hover/link:opacity-100 group-hover/link:translate-x-0 transition-all duration-300"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @foreach($categories as $category)
                                            <li class="px-3 py-0.5">
                                                <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="block px-4 py-2.5 rounded-lg hover:bg-gray-50 hover:text-black transition-all duration-300 font-medium text-gray-600 flex items-center justify-between group/link">
                                                    <span>{{ $category->name }}</span>
                                                    <i class="ph ph-caret-right text-xs opacity-0 -translate-x-2 group-hover/link:opacity-100 group-hover/link:translate-x-0 transition-all duration-300"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ url('about-us') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->is('about-us') ? 'active' : '' }}"> About Us </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ url('contact-us') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->is('contact-us') ? 'active' : '' }}"> Contact Us </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ route('frontend.track.order') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.track.order') ? 'active' : '' }}"> <i class="ph ph-truck text-lg"></i> Track Order </a>
                            </li>
                            @if(!empty($hasActiveDeals))
                            <li class="h-full relative">
                                <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 font-bold {{ request()->get('filter') === 'hot-deals' ? 'active' : '' }}"
                                   style="color: #9A0002;">
                                    🔥 Hot Deals
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="right flex items-center gap-4">
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
                                <img src="{{ asset('frontend/assets/images/' . $setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" style="max-width: 160px; height: 45px; object-fit: contain;">
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
                            <li style="border-bottom: 1px solid #f3f4f6; padding-bottom: 12px; margin-bottom: 12px;">
                                <a href="{{ route('frontend.home') }}" class="text-lg font-semibold flex items-center" style="{{ request()->routeIs('frontend.home') ? 'color: #9A0002;' : '' }}">Home</a>
                            </li>
                            <li style="border-bottom: 1px solid #f3f4f6; padding-bottom: 12px; margin-bottom: 12px;">
                                <a href="{{ route('frontend.shop') }}" class="text-lg font-semibold flex items-center" style="{{ request()->routeIs('frontend.shop') ? 'color: #9A0002;' : '' }}">Shop</a>
                            </li>
                            <li>
                                <div class="text-lg font-semibold flex items-center mt-2 cursor-pointer">Categories</div>
                                <ul class="pl-4 mt-3 space-y-3">
                                    @if(!empty($hasActiveDeals))
                                        <li style="border-bottom: 1px solid #f3f4f6; padding-bottom: 8px; margin-bottom: 8px;">
                                            <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}" class="text-base font-bold block" style="color: #9A0002;">🔥 Hot Deals</a>
                                        </li>
                                    @endif
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="text-base text-gray-600 block">{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
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