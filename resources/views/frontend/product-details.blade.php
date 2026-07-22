@extends('frontend.layouts.master')

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}" />
@endpush
    <!-- Size Guide Modal -->
    <div id="sizeGuideModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
        <div style="background-color: white; border-radius: 0.75rem; max-width: 800px; width: 100%; margin: 0 1rem; padding: 1.5rem; position: relative;">
            <button id="closeSizeGuideBtn" style="position: absolute; top: 1rem; right: 1rem; color: #6b7280; background: none; border: none; cursor: pointer;">
                <i class="ph ph-x" style="font-size: 1.5rem;"></i>
            </button>
            <h3 class="heading4 mb-4 text-center">Size Guide</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Size</th>
                            <th style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Chest (Inches)</th>
                            <th style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Length (Inches)</th>
                            <th style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Sleeve (Inches)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">S</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">38</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">27</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">8</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">M</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">40</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">28</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">8.5</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">L</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">42</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">29</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">9</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">XL</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">44</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">30</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">9.5</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">XXL</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">46</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">31</td>
                            <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">10</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 1rem; text-align: center;">Measurements are approximate and may vary slightly.</p>
        </div>
    </div>
<div class="product-detail sale">
            <div class="featured-product underwear filter-product-img md:py-20 py-14">
                <div class="container flex justify-between gap-y-6 flex-wrap">
                    <div class="list-img md:w-1/2 md:pr-[45px] w-full flex-shrink-0">
                        <div class="sticky">
                            <div class="swiper mySwiper2 rounded-2xl overflow-hidden">
                                <div class="swiper-wrapper" id="main-image-wrapper">
                                    @if($product->images->count() > 0)
                                        @foreach($product->images as $image)
                                        <div class="swiper-slide main-image-slide" data-color-id="{{ $image->product_color_id ?? '' }}">
                                            <img src="{{ asset('storage/products/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full aspect-[3/4] object-cover" />
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <img src="https://placehold.co/600x800" alt="{{ $product->name }}" class="w-full aspect-[3/4] object-cover" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="swiper mySwiper mt-4">
                                <div class="swiper-wrapper">
                                    @if($product->images->count() > 0)
                                        @foreach($product->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset('storage/products/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full aspect-[3/4] object-cover rounded-xl" />
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide">
                                            <img src="https://placehold.co/100x133" alt="{{ $product->name }}" class="w-full aspect-[3/4] object-cover rounded-xl" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="swiper popup-img">
                            <span class="close-popup-btn absolute top-4 right-4 z-[2]">
                                <i class="ph ph-x text-3xl text-white"></i>
                            </span>
                            <div class="swiper-wrapper">
                                @if($product->images->count() > 0)
                                    @foreach($product->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/products/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full aspect-[3/4] object-cover rounded-xl" />
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                    <div class="product-item product-infor md:w-1/2 w-full lg:pl-[15px] md:pl-2">
                        <div class="flex justify-between">
                            <div>
                                <div class="product-category caption2 text-secondary font-semibold uppercase">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                <div class="product-name heading4 mt-1">{{ $product->name }}</div>
                            </div>
                            <div class="add-wishlist-btn w-10 h-10 flex-shrink-0 flex items-center justify-center border border-line cursor-pointer rounded-lg duration-300 hover:bg-black hover:text-white">
                                <i class="ph ph-heart text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-3">
                            <div class="rate flex">
                                <i class="ph-fill ph-star text-sm text-yellow"></i>
                                <i class="ph-fill ph-star text-sm text-yellow"></i>
                                <i class="ph-fill ph-star text-sm text-yellow"></i>
                                <i class="ph-fill ph-star text-sm text-yellow"></i><i class="ph-fill ph-star text-sm text-yellow"></i>
                            </div>
                            <span class="caption1 text-secondary">(0 reviews)</span>
                        </div>
                        @php
                            $discountService = app(\App\Services\DiscountService::class);
                            $priceInfo = $discountService->resolvePrice($product);
                        @endphp
                        <div class="flex items-center gap-3 flex-wrap mt-5 pb-6 border-b border-line">
                            @if($priceInfo['has_discount'])
                                <div class="product-price heading5" style="color:#9A0002;">৳{{ number_format($priceInfo['discounted_price'], 2) }}</div>
                                <div class="w-px h-4 bg-line"></div>
                                <div class="product-origin-price font-normal text-secondary2">
                                    <del>৳{{ number_format($priceInfo['original_price'], 2) }}</del>
                                </div>
                                <div class="product-sale caption2 font-semibold text-white px-3 py-0.5 inline-block rounded-full" style="background:#9A0002;">
                                    -{{ round($priceInfo['discount_pct']) }}%
                                </div>
                            @else
                                <div class="product-price heading5">৳{{ number_format($priceInfo['discounted_price'], 2) }}</div>
                                @if($product->purchase_price > $product->sale_price)
                                    <div class="w-px h-4 bg-line"></div>
                                    <div class="product-origin-price font-normal text-secondary2">
                                        <del>৳{{ number_format($product->purchase_price, 2) }}</del>
                                    </div>
                                    <div class="product-sale caption2 font-semibold bg-green px-3 py-0.5 inline-block rounded-full">
                                        -{{ round((($product->purchase_price - $product->sale_price) / $product->purchase_price) * 100) }}%
                                    </div>
                                @endif
                            @endif
                            <div class="product-description w-full text-secondary mt-3">{!! $product->description ?? $product->short_description !!}</div>
                        </div>
                        <div class="list-action mt-6">
                            
                            <div class="choose-color">
                                <div class="text-title">Colors: <span class="text-title color"></span></div>
                                <div class="list-color flex items-center gap-2 flex-wrap mt-3">
                                    @if($product->variations->count() > 0)
    @php $uniqueColors = $product->variations->pluck('color')->unique('id')->filter(); @endphp
    @foreach($uniqueColors as $color)
        <div class="color-item w-10 h-10 rounded-full duration-300 relative" style="background-color: {{ $color->code }}; border: 1px solid #e5e7eb; cursor: pointer;" data-color-id="{{ $color->id }}" title="{{ $color->name }}">
            <div class="tag-action bg-black text-white caption2 capitalize px-1.5 py-0.5 rounded-sm">{{ $color->name }}</div>
        </div>
    @endforeach
@endif
                                </div>
                            </div>
                            <div class="choose-size mt-5">
                                <div class="heading flex items-center justify-between">
                                    <div class="text-title">Size: <span class="text-title size"></span></div>
                                    <div class="caption1 size-guide text-red underline">Size Guide</div>
                                </div>
                                <div class="list-size flex items-center gap-2 flex-wrap mt-3">
                                    @if($product->variations->count() > 0)
    @php $uniqueSizes = $product->variations->pluck('size')->unique('id')->filter(); @endphp
    @foreach($uniqueSizes as $size)
        <div class="size-item w-10 h-10 rounded-full flex items-center justify-center font-semibold duration-300 relative" style="border: 1px solid #e5e7eb; cursor: pointer;" data-size-id="{{ $size->id }}" title="{{ $size->name }}">
            {{ $size->name }}
        </div>
    @endforeach
@endif
                                </div>
                            </div>
                            <div class="text-title mt-5">Quantity:</div>
                            <div class="choose-quantity flex items-center max-xl:flex-wrap lg:justify-between gap-5 mt-3">
                                <div class="quantity-block py-1.5 px-1.5 flex items-center justify-between rounded-full border border-gray-200 sm:w-[150px] w-[130px] flex-shrink-0 bg-gray-50 shadow-sm">
                                    <i class="ph-bold ph-minus body1 w-10 h-10 flex items-center justify-center rounded-full bg-white shadow-sm hover:bg-black hover:text-white transition-colors duration-300 cursor-pointer"></i>
                                    <div class="quantity body1 font-semibold">1</div>
                                    <i class="ph-bold ph-plus body1 w-10 h-10 flex items-center justify-center rounded-full bg-white shadow-sm hover:bg-black hover:text-white transition-colors duration-300 cursor-pointer"></i>
                                </div>
                                <div class="add-cart-btn button-main whitespace-nowrap w-full text-center bg-white text-black border border-black" data-id="{{ $product->id }}">Add To Cart</div>
                            </div>
                            <div class="button-block mt-5">
                                <a href="{{ route('frontend.checkout') }}" class="button-main w-full text-center">Buy It Now</a>
                            </div>
                            <div class="more-infor mt-6">
                                <div class="flex items-center gap-4 flex-wrap">
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-arrow-clockwise body1"></i>
                                        <div class="text-title">Delivery & Return</div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-question body1"></i>
                                        <div class="text-title">Ask A Question</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <i class="ph ph-timer body1"></i>
                                    <div class="text-title">Estimated Delivery:</div>
                                    <div class="text-secondary">{{ now()->addDays(3)->format('d F') }} - {{ now()->addDays(7)->format('d F') }}</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <i class="ph ph-eye body1"></i>
                                    <div class="text-title">{{ rand(10, 50) }}</div>
                                    <div class="text-secondary">people viewing this product right now!</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <div class="text-title">SKU:</div>
                                    <div class="text-secondary">{{ $product->sku }}</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <div class="text-title">Categories:</div>
                                    <div class="list-category text-secondary">{{ $product->category->name ?? '' }}{{ $product->subCategory ? ', ' . $product->subCategory->name : '' }}</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <div class="text-title">Tag:</div>
                                    <div class="list-tag text-secondary">{{ $product->seo_tags ?? '' }}</div>
                                </div>
                            </div>
                            <div class="list-payment mt-7">
                                <div class="main-content lg:pt-8 pt-6 lg:pb-6 pb-4 sm:px-4 px-3 border border-line rounded-xl relative max-md:w-2/3 max-sm:w-full">
                                    <div class="heading6 px-5 bg-white absolute -top-[14px] left-1/2 -translate-x-1/2 whitespace-nowrap">Guranteed safe checkout</div>
                                    <div class="list grid grid-cols-6">
                                        <div class="item flex items-center justify-center lg:px-3 px-1">
                                            <img src="{{ asset('frontend/images/payment/Frame-0.png') }}" alt="payment" class="w-full" />
                                        </div>
                                        <div class="item flex items-center justify-center lg:px-3 px-1">
                                            <img src="{{ asset('frontend/images/payment/Frame-1.png') }}" alt="payment" class="w-full" />
                                        </div>
                                        <div class="item flex items-center justify-center lg:px-3 px-1">
                                            <img src="{{ asset('frontend/images/payment/Frame-2.png') }}" alt="payment" class="w-full" />
                                        </div>
                                        <div class="item flex items-center justify-center lg:px-3 px-1">
                                            <img src="{{ asset('frontend/images/payment/Frame-3.png') }}" alt="payment" class="w-full" />
                                        </div>
                                        <div class="item flex items-center justify-center lg:px-3 px-1">
                                            <img src="{{ asset('frontend/images/payment/Frame-4.png') }}" alt="payment" class="w-full" />
                                        </div>
                                        <div class="item flex items-center justify-center lg:px-3 px-1">
                                            <img src="{{ asset('frontend/images/payment/Frame-5.png') }}" alt="payment" class="w-full" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="get-it mt-6 pb-8 border-b border-line">
                            <div class="heading5">Get it today</div>
                            <div class="item flex items-center gap-3 mt-4">
                                <div class="icon-delivery-truck text-4xl"></div>
                                <div>
                                    <div class="text-title">Free shipping</div>
                                    <div class="caption1 text-secondary mt-1">Free shipping on orders over $75.</div>
                                </div>
                            </div>
                            <div class="item flex items-center gap-3 mt-4">
                                <div class="icon-phone-call text-4xl"></div>
                                <div>
                                    <div class="text-title">Support everyday</div>
                                    <div class="caption1 text-secondary mt-1">Support from 8:30 AM to 10:00 PM everyday</div>
                                </div>
                            </div>
                            <div class="item flex items-center gap-3 mt-4">
                                <div class="icon-return text-4xl"></div>
                                <div>
                                    <div class="text-title">100 Day Returns</div>
                                    <div class="caption1 text-secondary mt-1">Not impressed? Get a refund. You have 100 days to break our hearts.</div>
                                </div>
                            </div>
                        </div>
                                                  @if($relatedProducts->count() > 0)
                          <div class="list-product hide-product-sold menu-main mt-6">
                              <div class="heading5 pb-4">You'll love this too</div>
                              <div class="list-collection">
                                  <div class="swiper swiper-product-scroll h-full relative">
                                      <div class="swiper-wrapper">
                                          @foreach($relatedProducts as $relatedProduct)
                                          <div class="swiper-slide">
                                              @include('frontend.partials.product-item', ['product' => $relatedProduct])
                                          </div>
                                          @endforeach
                                      </div>
                                      <div class="swiper-scrollbar"></div>
                                  </div>
                              </div>
                          </div>
                          @endif
                    </div>
                </div>
            </div>
                        <div class="desc-tab md:pb-20 pb-10">
                <div class="container">
                    <div class="flex items-center justify-center w-full">
                        <div class="menu-tab flex items-center md:gap-[60px] gap-8">
                            <div class="tab-item heading5 has-line-before text-secondary2 hover:text-black duration-300 active">Description</div>
                        </div>
                    </div>
                    <div class="desc-block mt-8">
                        <div class="desc-item description" data-item="Description">
                            <div class="w-full text-secondary">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@push('scripts')
<script>
        // Swiper initialization for related products
        if (document.querySelector('.swiper-product-scroll')) {
            var swiperCollection = new Swiper(".swiper-product-scroll", {
                scrollbar: {
                    el: ".swiper-scrollbar",
                    hide: true,
                },
                loop: false,
                slidesPerView: 2,
                spaceBetween: 16,
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    1280: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                },
            });
        }
        
        // Color selection logic
        document.addEventListener('DOMContentLoaded', function() {
            const colorItems = document.querySelectorAll('.color-item');
            const mainSwiper = document.querySelector('.mySwiper2')?.swiper;
            
            colorItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Update active state
                    colorItems.forEach(i => {
                        i.classList.remove('active');
                        i.style.border = '1px solid #e5e7eb';
                        i.style.transform = 'scale(1)';
                    });
                    this.classList.add('active');
                    this.style.border = '2px solid #000';
                    this.style.transform = 'scale(1.1)';
                    
                    // Update text
                    const colorName = this.getAttribute('title');
                    document.querySelector('.choose-color .text-title.color').textContent = colorName;
                    
                    // Change image slider
                    const colorId = this.getAttribute('data-color-id');
                    if (mainSwiper && colorId) {
                        const slides = document.querySelectorAll('.main-image-slide');
                        for (let i = 0; i < slides.length; i++) {
                            if (slides[i].getAttribute('data-color-id') === colorId) {
                                mainSwiper.slideTo(i);
                                break;
                            }
                        }
                    }
                });
            });
            
            // Image Hover Zoom Logic
            const mainImageSlides = document.querySelectorAll('.main-image-slide');
            mainImageSlides.forEach(slide => {
                const img = slide.querySelector('img');
                
                slide.style.overflow = 'hidden';
                slide.style.cursor = 'zoom-in';
                img.style.transition = 'transform 0.15s ease-out';
                
                slide.addEventListener('mousemove', function(e) {
                    const rect = slide.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const xPercent = (x / rect.width) * 100;
                    const yPercent = (y / rect.height) * 100;
                    
                    img.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                    img.style.transform = 'scale(2)';
                });
                
                slide.addEventListener('mouseleave', function() {
                    img.style.transformOrigin = 'center center';
                    img.style.transform = 'scale(1)';
                });
            });

            // Size Guide Modal Logic
            const sizeGuideBtn = document.querySelector('.size-guide');
            const sizeGuideModal = document.getElementById('sizeGuideModal');
            const closeSizeGuideBtn = document.getElementById('closeSizeGuideBtn');

            if (sizeGuideBtn && sizeGuideModal) {
                sizeGuideBtn.addEventListener('click', () => {
                    sizeGuideModal.style.display = 'flex';
                });
                closeSizeGuideBtn.addEventListener('click', () => {
                    sizeGuideModal.style.display = 'none';
                });
                sizeGuideModal.addEventListener('click', (e) => {
                    if (e.target === sizeGuideModal) {
                        sizeGuideModal.style.display = 'none';
                    }
                });
                sizeGuideBtn.style.cursor = 'pointer';
            }
            // Size selection logic
            const sizeItems = document.querySelectorAll('.size-item');
            sizeItems.forEach(item => {
                item.addEventListener('click', function() {
                    sizeItems.forEach(i => {
                        i.classList.remove('active');
                        i.style.border = '1px solid #e5e7eb';
                        i.style.backgroundColor = 'transparent';
                        i.style.color = '#000';
                    });
                    this.classList.add('active');
                    this.style.border = '1px solid #000';
                    this.style.backgroundColor = '#000';
                    this.style.color = '#fff';
                    
                    const sizeName = this.getAttribute('title');
                    document.querySelector('.choose-size .text-title.size').textContent = sizeName;
                });
            });
        });
    </script>

@endpush
@endsection


















