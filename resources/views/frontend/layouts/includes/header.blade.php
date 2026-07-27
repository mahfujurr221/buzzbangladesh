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
                        @if(!empty($hasActiveDeals))
                        <li class="h-full relative">
                            <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}"
                               class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 font-bold {{ request()->get('filter') === 'hot-deals' ? 'active' : '' }}"
                               style="color: #9A0002;">
                                🔥 Hot Deals
                            </a>
                        </li>
                        @endif
                        @foreach($activeSeasons as $season)
                            <li class="h-full relative">
                                <a href="{{ route('frontend.shop', ['season' => $season->slug]) }}" class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->get('season') === $season->slug ? 'active' : '' }}">
                                    {{ $season->name }}
                                </a>
                            </li>
                        @endforeach
                        <style>
                            .header-dropdown-menu {
                                display: none;
                                position: absolute;
                                top: 100%;
                                left: 0;
                                min-width: 150px;
                                background-color: white;
                                border: 1px solid #f3f4f6;
                                border-radius: 0.5rem;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                                z-index: 50;
                                padding: 0.5rem 0;
                            }
                            .header-dropdown-wrapper:hover .header-dropdown-menu {
                                display: block;
                            }
                        </style>
                        @foreach($categories as $category)
                            <li class="h-full relative {{ $category->subCategories->count() > 0 ? 'header-dropdown-wrapper' : '' }}">
                                <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="whitespace-nowrap text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->get('category') === $category->slug ? 'active' : '' }}">
                                    {{ $category->name }}
                                    @if($category->subCategories->count() > 0)
                                        <i class="ph ph-caret-down text-xs ml-1 opacity-70"></i>
                                    @endif
                                </a>
                                @if($category->subCategories->count() > 0)
                                    <div class="header-dropdown-menu">
                                        @foreach($category->subCategories as $sub)
                                            <a href="{{ route('frontend.shop', ['subcategory' => $sub->slug]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#9A0002] transition-colors whitespace-nowrap" style="text-transform: none;">
                                                {{ $sub->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </li>
                        @endforeach

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
        <div class="menu-container h-full" style="background-color: #FDFBF7;">
            <div class="container h-full">
                <div class="menu-main h-full flex flex-col">
                    <div class="heading py-4 px-5 relative flex items-center justify-between border-b" style="border-color: rgba(154, 0, 2, 0.15); background-color: #ffffff;">
                        <a href="{{ route('frontend.home') }}" class="logo block">
                            @if($setting?->logo)
                                <img src="{{ asset('frontend/assets/images/' . $setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" class="h-8 w-auto object-contain" style="max-width: 130px;">
                            @else
                                <div class="text-2xl font-bold" style="color: #9A0002;">{{ $setting?->site_name ?? 'Buzz' }}</div>
                            @endif
                        </a>
                        <div class="close-menu-mobile-btn w-10 h-10 rounded-full flex items-center justify-center cursor-pointer shadow-sm bg-white border" style="border-color: rgba(154, 0, 2, 0.2);">
                            <i class="ph ph-x text-xl" style="color: #9A0002;"></i>
                        </div>
                    </div>
                    
                    <div class="list-nav flex-1 overflow-y-auto px-5 py-6">
                        <ul class="flex flex-col gap-4">
                            @if(!empty($hasActiveDeals))
                                <li>
                                    <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}" class="mobile-nav-item-link flex items-center justify-between p-4 rounded-xl shadow-sm transition-all duration-300" style="background-color: #ffffff; border: 1px solid rgba(154, 0, 2, 0.1); {{ request()->get('filter') === 'hot-deals' ? 'border-color: #9A0002; color: #9A0002; box-shadow: 0 4px 10px rgba(154, 0, 2, 0.1);' : 'color: #9A0002;' }}">
                                        <span class="text-lg font-bold flex items-center gap-2">Hot Deals <i class="ph-fill ph-fire text-lg animate-pulse" style="color: #ff4500;"></i></span>
                                        <i class="ph ph-caret-right text-sm opacity-50"></i>
                                    </a>
                                </li>
                            @endif
                            @foreach($activeSeasons as $season)
                                <li>
                                    <a href="{{ route('frontend.shop', ['season' => $season->slug]) }}" class="mobile-nav-item-link flex items-center justify-between p-4 rounded-xl shadow-sm transition-all duration-300" style="background-color: #ffffff; border: 1px solid rgba(154, 0, 2, 0.1); {{ request()->get('season') === $season->slug ? 'border-color: #9A0002; color: #9A0002; box-shadow: 0 4px 10px rgba(154, 0, 2, 0.1);' : 'color: #333333;' }}">
                                        <span class="text-lg font-bold">{{ $season->name }}</span>
                                        <i class="ph ph-caret-right text-sm opacity-50"></i>
                                    </a>
                                </li>
                            @endforeach
                            @foreach($categories as $category)
                                <li>
                                    @if($category->subCategories->count() > 0)
                                        <div class="mobile-cat-accordion flex flex-col rounded-xl shadow-sm overflow-hidden transition-all duration-300" style="background-color: #ffffff; border: 1px solid rgba(154, 0, 2, 0.1);">
                                            <div class="flex items-center justify-between p-4 cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.accordion-icon').classList.toggle('ph-caret-down'); this.querySelector('.accordion-icon').classList.toggle('ph-caret-up');">
                                                <span class="text-lg font-bold" style="color: #333333;">{{ $category->name }}</span>
                                                <i class="ph ph-caret-down text-sm opacity-50 accordion-icon"></i>
                                            </div>
                                            <div class="hidden bg-gray-50 flex flex-col border-t border-gray-100">
                                                <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="mobile-nav-item-link px-6 py-3 border-b border-gray-100 text-[#9A0002] font-semibold">
                                                    All {{ $category->name }}
                                                </a>
                                                @foreach($category->subCategories as $sub)
                                                    <a href="{{ route('frontend.shop', ['subcategory' => $sub->slug]) }}" class="mobile-nav-item-link px-6 py-3 border-b border-gray-100 text-gray-700 hover:text-[#9A0002]">
                                                        {{ $sub->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="mobile-nav-item-link flex items-center justify-between p-4 rounded-xl shadow-sm transition-all duration-300" style="background-color: #ffffff; border: 1px solid rgba(154, 0, 2, 0.1); {{ request()->get('category') === $category->slug ? 'border-color: #9A0002; color: #9A0002; box-shadow: 0 4px 10px rgba(154, 0, 2, 0.1);' : 'color: #333333;' }}">
                                            <span class="text-lg font-bold">{{ $category->name }}</span>
                                            <i class="ph ph-caret-right text-sm opacity-50"></i>
                                        </a>
                                    @endif
                                </li>
                            @endforeach

                            <li>
                                <a href="{{ route('frontend.track.order') }}" class="mobile-nav-item-link flex items-center justify-between p-4 rounded-xl shadow-sm transition-all duration-300" style="background-color: #ffffff; border: 1px solid rgba(154, 0, 2, 0.1); {{ request()->routeIs('frontend.track.order') ? 'border-color: #9A0002; color: #9A0002; box-shadow: 0 4px 10px rgba(154, 0, 2, 0.1);' : 'color: #333333;' }}">
                                    <span class="text-lg font-bold">Track Order</span>
                                    <i class="ph ph-caret-right text-sm opacity-50"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.mobile-nav-item-link');
            const mobileMenu = document.getElementById('menu-mobile');
            
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if(mobileMenu) {
                        mobileMenu.classList.remove('open');
                    }
                });
            });
        });
    </script>

</div>
