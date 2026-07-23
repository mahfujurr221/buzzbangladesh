@extends('frontend.layouts.master')

@section('content')
<div class="slider-block style-one bg-linear xl:h-[860px] lg:h-[800px] md:h-[580px] sm:h-[500px] h-[350px] max-[420px]:h-[320px] w-full">
                <div class="slider-main h-full w-full">
                    <div class="swiper swiper-slider h-full relative">
                        <div class="swiper-wrapper">
                            @foreach($banners as $banner)
                            <div class="swiper-slide">
                                <div class="slider-item h-full w-full relative">
                                    <div class="container w-full h-full flex items-center relative">
                                        <div class="text-content basis-1/2">
                                            <div class="text-sub-display">{{ $banner->subtitle ?? 'Sale! Up To 50% Off!' }}</div>
                                            <div class="text-display md:mt-5 mt-2">{{ $banner->title ?? 'Summer Sale Collections' }}</div>
                                            <a href="{{ $banner->button_link ?? route('frontend.shop') }}" class="button-main md:mt-8 mt-3">{{ $banner->button_text ?? 'Shop Now' }} </a>
                                        </div>
                                        <div class="sub-img absolute sm:w-1/2 w-3/5 2xl:right-0 right-0 top-10 bottom-0 flex justify-end items-end">
                                            <img src="{{ asset($banner->image) }}" alt="Banner Image" class="w-full h-full object-contain object-bottom" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="what-new-block filter-product-block md:pt-20 pt-10">
            <div class="container">
                <div class="heading flex flex-col items-center text-center">
                    <div class="heading3">What's new</div>
                    <div class="menu-tab bg-surface rounded-2xl mt-6">
                        <div class="menu flex items-center gap-2 p-1">
                            <div class="indicator absolute top-1 bottom-1 bg-white rounded-full shadow-md duration-300"></div>
                            <div class="tab-item custom-tab-item relative text-secondary text-button-uppercase py-2 px-5 cursor-pointer duration-300 hover:text-black active" data-item="all">All</div>
                            @foreach($categories as $category)
                            <div class="tab-item custom-tab-item relative text-secondary text-button-uppercase py-2 px-5 cursor-pointer duration-300 hover:text-black" data-item="{{ Str::slug($category->name) }}">{{ $category->name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="list-product custom-tab-content hide-product-sold grid xl:grid-cols-4 sm:grid-cols-3 grid-cols-2 md:gap-[30px] gap-4 md:mt-10 mt-6" id="tab-all">
                      @foreach($latestProducts as $product)
                          @include('frontend.partials.product-item')
                      @endforeach
                  </div>
                  
                  @foreach($categories as $category)
                  <div class="list-product custom-tab-content hide-product-sold grid xl:grid-cols-4 sm:grid-cols-3 grid-cols-2 md:gap-[30px] gap-4 md:mt-10 mt-6 hidden" id="tab-{{ Str::slug($category->name) }}">
                      @foreach($category->products as $product)
                          @include('frontend.partials.product-item')
                      @endforeach
                  </div>
                  @endforeach
            </div>
        </div>

        <div class="collection-block md:pt-20 pt-10">
            <div class="container">
                <div class="heading3 text-center">Explore Collections</div>
            </div>
            <div class="list-collection relative section-swiper-navigation md:mt-10 mt-6 sm:px-5 px-4">
                <div class="swiper-button-prev lg:left-10 left-6"></div>
                <div class="swiper swiper-collection h-full relative">
                    <div class="swiper-wrapper">
                        @foreach($allCategories as $collection)
                        <div class="swiper-slide">
                            <a href="{{ route('frontend.shop') }}?category={{ $collection->id }}" class="collection-item block relative rounded-2xl overflow-hidden cursor-pointer">
                                <div class="bg-img aspect-[3/4] w-full">
                                    <img src="{{ $collection->logo ? asset('backend/images/' . $collection->logo) : asset('backend/images/products/placeholder.png') }}" alt="{{ $collection->name }}" class="w-full h-full object-cover" />
                                </div>
                                <div class="collection-name heading5 text-center sm:bottom-8 bottom-4 lg:w-[200px] md:w-[160px] w-[100px] md:py-3 py-1.5 bg-white rounded-xl duration-500">{{ $collection->name }}</div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="swiper-button-next lg:right-10 right-6"></div>
            </div>
        </div>

        <div class="tab-features-block filter-product-block md:pt-20 pt-10">
            <div class="container">
                <div class="heading flex flex-col items-center text-center">
                    <div class="menu-tab bg-surface rounded-2xl">
                        <div class="menu flex items-center gap-2 p-1">
                            <div class="indicator absolute top-1 bottom-1 bg-white rounded-full shadow-md duration-300"></div>
                            <div class="tab-item custom-tab-item-2 relative text-secondary heading5 py-2 px-5 cursor-pointer duration-500 hover:text-black active" data-item="best-sellers">best sellers</div>
                            <div class="tab-item custom-tab-item-2 relative text-secondary heading5 py-2 px-5 cursor-pointer duration-500 hover:text-black" data-item="on-sale">on sale</div>
                            <div class="tab-item custom-tab-item-2 relative text-secondary heading5 py-2 px-5 cursor-pointer duration-500 hover:text-black" data-item="new-arrivals">new arrivals</div>
                        </div>
                    </div>
                </div>
                
                <div class="list-product custom-tab-content-2 hide-product-sold grid xl:grid-cols-4 sm:grid-cols-3 grid-cols-2 md:gap-[30px] gap-4 md:mt-10 mt-6" id="tab2-best-sellers">
                    @foreach($bestSellers as $product)
                        @include('frontend.partials.product-item')
                    @endforeach
                </div>
                
                <div class="list-product custom-tab-content-2 hidden hide-product-sold grid xl:grid-cols-4 sm:grid-cols-3 grid-cols-2 md:gap-[30px] gap-4 md:mt-10 mt-6" id="tab2-on-sale">
                    @foreach($onSale as $product)
                        @include('frontend.partials.product-item')
                    @endforeach
                </div>
                
                <div class="list-product custom-tab-content-2 hidden hide-product-sold grid xl:grid-cols-4 sm:grid-cols-3 grid-cols-2 md:gap-[30px] gap-4 md:mt-10 mt-6" id="tab2-new-arrivals">
                    @foreach($newArrivals as $product)
                        @include('frontend.partials.product-item')
                    @endforeach
                </div>
            </div>
        </div>

        <div class="banner-block style-one grid sm:grid-cols-2 gap-5 md:pt-20 pt-10">
            <a href="{{ $setting?->promo_banner_1_link ?? route('frontend.shop') }}" class="banner-item relative block overflow-hidden duration-500">
                <div class="banner-img aspect-[4/3] w-full" style="aspect-ratio: 4/3;">
                    <img src="{{ $setting?->promo_banner_1 ? asset($setting->promo_banner_1) : asset('backend/images/products/placeholder.png') }}" class="duration-1000 w-full h-full object-cover" alt="img" />
                </div>
                <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
                    <div class="heading2 text-white">{{ $setting?->promo_banner_1_title ?? 'Best Sellers' }}</div>
                    <div class="text-button text-white relative inline-block pb-1 border-b-2 border-white duration-500 mt-2">Shop Now</div>
                </div>
            </a>
            <a href="{{ $setting?->promo_banner_2_link ?? route('frontend.shop') }}" class="banner-item relative block overflow-hidden duration-500">
                <div class="banner-img aspect-[4/3] w-full" style="aspect-ratio: 4/3;">
                    <img src="{{ $setting?->promo_banner_2 ? asset($setting->promo_banner_2) : asset('backend/images/products/placeholder.png') }}" class="duration-1000 w-full h-full object-cover" alt="img" />
                </div>
                <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
                    <div class="heading2 text-white">{{ $setting?->promo_banner_2_title ?? 'New Arrivals' }}</div>
                    <div class="text-button text-white relative inline-block pb-1 border-b-2 border-white duration-500 mt-2">Shop Now</div>
                </div>
            </a>
        </div>

        <div class="benefit-block md:pt-20 pt-10">
            <div class="container">
                <div class="list-benefit grid items-start lg:grid-cols-4 grid-cols-2 gap-[30px]">
                    <div class="benefit-item flex flex-col items-center justify-center">
                        <i class="icon-phone-call lg:text-7xl text-5xl"></i>
                        <div class="heading6 text-center mt-5">24/7 Customer Service</div>
                        <div class="caption1 text-secondary text-center mt-3">We're here to help you with any questions or concerns you have, 24/7.</div>
                    </div>
                    <div class="benefit-item flex flex-col items-center justify-center">
                        <i class="icon-return lg:text-7xl text-5xl"></i>
                        <div class="heading6 text-center mt-5">14-Day Money Back</div>
                        <div class="caption1 text-secondary text-center mt-3">If you're not satisfied with your purchase, simply return it within 14 days for a refund.</div>
                    </div>
                    <div class="benefit-item flex flex-col items-center justify-center">
                        <i class="icon-guarantee lg:text-7xl text-5xl"></i>
                        <div class="heading6 text-center mt-5">Our Guarantee</div>
                        <div class="caption1 text-secondary text-center mt-3">We stand behind our products and services and guarantee your satisfaction.</div>
                    </div>
                    <div class="benefit-item flex flex-col items-center justify-center">
                        <i class="icon-delivery-truck lg:text-7xl text-5xl"></i>
                        <div class="heading6 text-center mt-5">Shipping worldwide</div>
                        <div class="caption1 text-secondary text-center mt-3">We ship our products worldwide, making them accessible to customers everywhere.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonial-block md:pt-20 md:pb-16 pt-10 pb-8 md:mt-20 mt-10 bg-surface">
            <div class="container">
                <div class="heading3 text-center">What People Are Saying</div>
                <div class="list-testimonial pagination-mt40 md:mt-10 mt-6">
                    <div class="swiper swiper-list-testimonial h-full relative">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-item style-one h-full">
                                    <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                        <div class="flex items-center gap-1">
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                        </div>
                                        <div class="heading6 title mt-4">Variety of Styles!</div>
                                        <div class="desc mt-2">"Fantastic shop! Great selection, fair prices, and friendly staff. Highly recommended. The quality of the products is exceptional, and the prices are very reasonable!"</div>
                                        <div class="text-button name mt-4">Lisa K.</div>
                                        <div class="caption2 date text-secondary2 mt-1">August 13, 2024</div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-item style-one h-full">
                                    <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                        <div class="flex items-center gap-1">
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                        </div>
                                        <div class="heading6 title mt-4">Quality of Clothing!</div>
                                        <div class="desc mt-2">"Anvouge's fashion collection is a game-changer! Their unique and trendy pieces have completely transformed my style. It's comfortable, stylish, and always on-trend."</div>
                                        <div class="text-button name mt-4">Elizabeth A.</div>
                                        <div class="caption2 date text-secondary2 mt-1">August 13, 2024</div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-item style-one h-full">
                                    <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                        <div class="flex items-center gap-1">
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                        </div>
                                        <div class="heading6 title mt-4">Customer Service!</div>
                                        <div class="desc mt-2">"I absolutely love this shop! The products are high-quality and the customer service is excellent. I always leave with exactly what I need and a smile on my face."</div>
                                        <div class="text-button name mt-4">Christin H.</div>
                                        <div class="caption2 date text-secondary2 mt-1">August 13, 2024</div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-item style-one h-full">
                                    <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                        <div class="flex items-center gap-1">
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                        </div>
                                        <div class="heading6 title mt-4">Quality of Clothing!</div>
                                        <div class="desc mt-2">"I can't get enough of Anvouge's high-quality clothing. It's comfortable, stylish, and always on-trend. The products are high-quality and the customer service is excellent."</div>
                                        <div class="text-button name mt-4">Emily G.</div>
                                        <div class="caption2 date text-secondary2 mt-1">August 13, 2024</div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-item style-one h-full">
                                    <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                        <div class="flex items-center gap-1">
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                            <i class="ph-fill ph-star text-yellow"></i>
                                        </div>
                                        <div class="heading6 title mt-4">Customer Service!</div>
                                        <div class="desc mt-2">"I love this shop! The products are always top-quality, and the staff is incredibly friendly and helpful. They go out of their way to make sure that I'm satisfied my purchase."</div>
                                        <div class="text-button name mt-4">Carolina C.</div>
                                        <div class="caption2 date text-secondary2 mt-1">August 13, 2024</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="instagram-block md:py-20 py-10">
            <div class="container">
                <div class="heading">
                    <div class="heading3 text-center">Buzz On Instagram</div>
                </div>
                <div class="list-instagram md:mt-10 mt-6">
                    <style>
                        .insta-hover-icon { background-color: white; color: black; }
                        .insta-hover-icon .icon-instagram { color: black; }
                        .item:hover .insta-hover-icon { background-color: var(--green) !important; color: white !important; }
                        .item:hover .insta-hover-icon .icon-instagram { color: white !important; }
                    </style>
                    <div class="swiper swiper-list-instagram">
                        <div class="swiper-wrapper">
                            @forelse($instagramFeeds as $feed)
                            <div class="swiper-slide">
                                <a href="{{ $feed->link ?? 'https://www.instagram.com/' }}" target="_blank" class="item relative block rounded-[32px] overflow-hidden">
                                    <img src="{{ asset($feed->image) }}" alt="Instagram Feed" class="h-full w-full duration-500 relative object-cover" style="aspect-ratio: 1/1;" />
                                    <div class="icon w-12 h-12 insta-hover-icon duration-300 flex items-center justify-center rounded-2xl absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1]">
                                        <div class="icon-instagram text-2xl duration-300"></div>
                                    </div>
                                </a>
                            </div>
                            @empty
                            @for($i=0; $i<=5; $i++)
                            <div class="swiper-slide">
                                <a href="https://www.instagram.com/" target="_blank" class="item relative block rounded-[32px] overflow-hidden">
                                    <img src="{{ asset('frontend/images/instagram/'.$i.'.png') }}" alt="Instagram {{ $i }}" class="h-full w-full duration-500 relative object-cover" style="aspect-ratio: 1/1;" />
                                    <div class="icon w-12 h-12 insta-hover-icon duration-300 flex items-center justify-center rounded-2xl absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1]">
                                        <div class="icon-instagram text-2xl duration-300"></div>
                                    </div>
                                </a>
                            </div>
                            @endfor
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Block Removed -->

@if(isset($flashModal) && $flashModal)
<!-- Flash Modal -->
<div class="modal-newsletter" id="modal-newsletter" data-delay="{{ $flashModal->delay_seconds * 1000 }}" data-id="{{ $flashModal->id }}">
    <div class="modal-newsletter-main relative mx-auto overflow-hidden shadow-2xl" style="max-width: 600px; border-radius: 1rem;">
        <div class="close-newsletter-btn" style="position: absolute; top: 12px; right: 12px; z-index: 100; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.5); color: white; border-radius: 50%; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='rgba(0,0,0,0.8)'" onmouseout="this.style.backgroundColor='rgba(0,0,0,0.5)'">
            <i class="ph ph-x" style="font-size: 18px;"></i>
        </div>
        <div class="modal-content relative h-full">
            @if($flashModal->link)
            <a href="{{ $flashModal->link }}" class="block w-full h-full cursor-pointer">
            @endif
                <img src="{{ asset($flashModal->image) }}" alt="{{ $flashModal->title }}" class="w-full h-auto block object-cover max-h-[80vh]">
            @if($flashModal->link)
            </a>
            @endif
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.custom-tab-item');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.custom-tab-content').forEach(c => c.classList.add('hidden'));
                
                // Show selected tab content
                const target = 'tab-' + this.getAttribute('data-item');
                const targetEl = document.getElementById(target);
                if(targetEl) {
                    targetEl.classList.remove('hidden');
                }
            });
        });
        
        const tabs2 = document.querySelectorAll('.custom-tab-item-2');
        tabs2.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs in this group
                tabs2.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Hide all tab contents in this group
                document.querySelectorAll('.custom-tab-content-2').forEach(c => c.classList.add('hidden'));
                
                // Show selected tab content
                const target = 'tab2-' + this.getAttribute('data-item');
                const targetEl = document.getElementById(target);
                if(targetEl) {
                    targetEl.classList.remove('hidden');
                }
            });
        });
    });
</script>
@endpush
