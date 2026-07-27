<div id="footer" class="footer">
    <div class="footer-main bg-linear">
                <div class="container">
                    <div class="content-footer md:py-[60px] py-10 flex justify-between flex-wrap gap-y-8">
                        <div class="company-infor basis-1/4 max-lg:basis-full pr-7">
                            <a href="{{ route('frontend.home') }}" class="logo inline-block">
                                @if($setting?->logo)
                                    <img src="{{ asset('frontend/assets/images/' . $setting?->logo) }}" alt="{{ $setting?->site_name ?? 'Logo' }}" class="h-10 md:h-12 lg:h-14 w-auto max-w-[150px] md:max-w-[240px] object-contain object-left">
                                @else
                                    <div class="heading3 w-fit">{{ $setting?->site_name ?? 'Buzz' }}</div>
                                @endif
                            </a>
                            <div class="flex gap-3 mt-3">
                                <div class="flex flex-col">
                                    <span class="text-button">Mail:</span>
                                    <span class="text-button mt-3">Phone:</span>
                                    <span class="text-button mt-3">Address:</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="">{{ $setting?->email ?? 'N/A' }}</span>
                                    <span class="mt-[14px]">{{ $setting?->phone ?? 'N/A' }}</span>
                                    <span class="mt-3 pt-1">{{ $setting?->address ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="right-content flex flex-wrap gap-y-8 basis-3/4 max-lg:basis-full">
                            <div class="list-nav flex justify-between basis-2/3 max-md:basis-full gap-4">
                                <!-- Information Column (Dynamic Pages) -->
                                <div class="item flex flex-col basis-1/3">
                                    <div class="text-button-uppercase pb-3">Information</div>
                                    @if(isset($footerPages) && $footerPages->count() > 0)
                                        @foreach($footerPages as $page)
                                            <a class="caption1 has-line-before duration-300 w-fit {{ !$loop->first ? 'pt-2' : '' }}" href="{{ route('frontend.page', $page->slug) }}">
                                                {{ $page->title }}
                                            </a>
                                        @endforeach
                                    @else
                                        <!-- Fallback if no pages exist -->
                                        <a class="caption1 has-line-before duration-300 w-fit" href="{{ url('contact-us') }}">Contact us</a>
                                        <a class="caption1 has-line-before duration-300 w-fit pt-2" href="{{ url('terms') }}">Terms & Conditions</a>
                                    @endif
                                </div>

                                <!-- Quick Shop Column (Dynamic Categories) -->
                                <div class="item flex flex-col basis-1/3">
                                    <div class="text-button-uppercase pb-3">Quick Shop</div>
                                    @if(isset($footerCategories) && $footerCategories->count() > 0)
                                        @foreach($footerCategories as $category)
                                            <a class="caption1 has-line-before duration-300 w-fit {{ !$loop->first ? 'pt-2' : '' }}" href="{{ route('frontend.shop', ['category' => $category->slug]) }}">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    @else
                                        <!-- Fallback if no categories exist -->
                                        <a class="caption1 has-line-before duration-300 w-fit" href="{{ route('frontend.shop') }}">Women</a>
                                        <a class="caption1 has-line-before duration-300 w-fit pt-2" href="{{ route('frontend.shop') }}">Men</a>
                                    @endif
                                </div>

                                <!-- Customer Services Column -->
                                <div class="item flex flex-col basis-1/3">
                                    <div class="text-button-uppercase pb-3">Customer Services</div>
                                    <a class="caption1 has-line-before duration-300 w-fit" href="{{ url('contact-us') }}">Contact Us</a>
                                    <a class="caption1 has-line-before duration-300 w-fit pt-2" href="{{ route('frontend.customer.dashboard') }}">My Account</a>
                                </div>
                            </div>
                            
                            <div class="newsletter basis-1/3 pl-7 max-md:basis-full max-md:pl-0">
                                <div class="text-button-uppercase">Newsletter</div>
                                <div class="caption1 mt-3">{{ $setting?->newsletter_text ?? 'Sign up for our newsletter and get 10% off your first purchase' }}</div>
                                <div class="input-block w-full h-[52px] mt-4">
                                    <form class="w-full h-full relative" action="#!">
                                        <input type="email" placeholder="Enter your e-mail" class="caption1 w-full h-full pl-4 pr-14 rounded-xl border border-line" required />
                                        <button class="w-[44px] h-[44px] flex items-center justify-center rounded-xl absolute top-1 right-1" style="background-color: #9A0002;">
                                            <i class="ph ph-arrow-right text-xl text-white"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="list-social flex items-center gap-6 mt-4">
                                    @if($setting?->facebook)
                                    <a href="{{ $setting?->facebook }}" target="_blank">
                                        <div class="icon-facebook text-2xl" style="color: #9A0002;"></div>
                                    </a>
                                    @endif
                                    @if($setting?->instagram)
                                    <a href="{{ $setting?->instagram }}" target="_blank">
                                        <div class="icon-instagram text-2xl" style="color: #9A0002;"></div>
                                    </a>
                                    @endif
                                    @if($setting?->twitter)
                                    <a href="{{ $setting?->twitter }}" target="_blank">
                                        <div class="icon-twitter text-2xl" style="color: #9A0002;"></div>
                                    </a>
                                    @endif
                                    @if($setting?->youtube)
                                    <a href="{{ $setting?->youtube }}" target="_blank">
                                        <div class="icon-youtube text-2xl" style="color: #9A0002;"></div>
                                    </a>
                                    @endif
                                    @if($setting?->pinterest)
                                    <a href="{{ $setting?->pinterest }}" target="_blank">
                                        <div class="icon-pinterest text-2xl" style="color: #9A0002;"></div>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-bottom py-3 flex items-center justify-between gap-5 max-lg:justify-center max-lg:flex-col border-t border-line">
                        <div class="left flex items-center gap-8">
                            <div class="copyright caption1 text-secondary">&copy; buzz bangladesh alright reserve</div>
                        </div>
                        <div class="right flex items-center gap-2">
                            <div class="caption1 text-secondary">developed by <a href="mailto:mahfujurr221@gmail.com" class="hover:text-black hover:underline transition-all duration-300">mahfujur rahman</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
