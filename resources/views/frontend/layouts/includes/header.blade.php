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
                            <img src="{{ asset($setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" class="h-10">
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
                            <li class="h-full relative">
                                <a href="{{ route('frontend.blog') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.blog') ? 'active' : '' }}"> Blog </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ route('frontend.about') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.about') ? 'active' : '' }}"> About Us </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ route('frontend.contact') }}" class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('frontend.contact') ? 'active' : '' }}"> Contact Us </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="right flex items-center gap-3">
                    <a href="{{ route('frontend.shop') }}" class="max-md:hidden">
                        <i class="ph ph-magnifying-glass text-2xl"></i>
                    </a>
                    <div class="open-cart-modal cursor-pointer">
                        <div class="cart-icon relative">
                            <i class="ph ph-handbag text-2xl"></i>
                            <span class="quantity cart-quantity absolute -right-1.5 -top-1.5 text-xs text-white bg-black w-4 h-4 flex items-center justify-center rounded-full">0</span>
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
                    <div class="heading py-2 relative flex items-center justify-center">
                        <div class="close-menu-mobile-btn absolute left-0 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-surface flex items-center justify-center cursor-pointer">
                            <i class="ph ph-x text-sm"></i>
                        </div>
                        <a href="{{ route('frontend.home') }}" class="logo text-3xl font-semibold text-center">
                            @if($setting?->logo)
                                <img src="{{ asset($setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" class="h-8 mx-auto">
                            @else
                                {{ $setting?->site_name ?? 'Buzz' }}
                            @endif
                        </a>
                    </div>
                    <div class="list-nav mt-6">
                        <ul>
                            <li>
                                <a href="{{ route('frontend.home') }}" class="text-xl font-semibold flex items-center justify-between {{ request()->routeIs('frontend.home') ? 'active' : '' }}">Home</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.shop') }}" class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('frontend.shop') ? 'active' : '' }}">Shop</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.blog') }}" class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('frontend.blog') ? 'active' : '' }}">Blog</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.about') }}" class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('frontend.about') ? 'active' : '' }}">About Us</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.contact') }}" class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('frontend.contact') ? 'active' : '' }}">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>