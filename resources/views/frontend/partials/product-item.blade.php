<div class="product-item grid-type">
    <div class="product-main cursor-pointer block" data-item="{{ $product->id }}">
        <div class="product-thumb bg-white relative overflow-hidden rounded-2xl block">
            <a href="{{ route('frontend.product.details', ['slug' => $product->slug ?? $product->id]) }}">
                @if($product->is_new_arrival)
                <div class="product-tag text-button-uppercase bg-green px-3 py-0.5 inline-block rounded-full absolute top-3 left-3 z-[1]">New</div>
                @endif
                @if($product->is_on_sale)
                <div class="product-tag text-button-uppercase text-white bg-red px-3 py-0.5 inline-block rounded-full absolute top-3 left-3 z-[1]" style="{{ $product->is_new_arrival ? 'margin-top: 32px;' : '' }}">Sale</div>
                @endif
                
                <div class="product-img w-full h-full aspect-[3/4]">
                    @php
                        $firstImg = $product->images->first() ? asset($product->images->first()->image_path) : asset('backend/images/products/placeholder.png');
                        $secondImg = $product->images->skip(1)->first() ? asset($product->images->skip(1)->first()->image_path) : $firstImg;
                    @endphp
                    <img class="w-full h-full object-cover duration-700" src="{{ $firstImg }}" alt="{{ $product->name }}" />
                    <img class="w-full h-full object-cover duration-700" src="{{ $secondImg }}" alt="{{ $product->name }}" />
                </div>
            </a>
            
            <div class="list-action-right absolute top-3 right-3 max-lg:hidden z-[2]">
                <div class="add-wishlist-btn w-[32px] h-[32px] flex items-center justify-center rounded-full bg-white duration-300 relative cursor-pointer">
                    <div class="tag-action bg-black text-white caption2 px-1.5 py-0.5 rounded-sm">Add To Wishlist</div>
                    <i class="ph ph-heart text-lg"></i>
                </div>
                <div class="compare-btn w-[32px] h-[32px] flex items-center justify-center rounded-full bg-white duration-300 relative mt-2 cursor-pointer">
                    <div class="tag-action bg-black text-white caption2 px-1.5 py-0.5 rounded-sm">Compare Product</div>
                    <i class="ph ph-arrow-counter-clockwise text-lg compare-icon"></i>
                    <i class="ph ph-check-circle text-lg checked-icon"></i>
                </div>
            </div>
            
            <div class="list-action grid grid-cols-2 gap-3 px-5 absolute w-full bottom-5 max-lg:hidden">
                <div class="quick-view-btn w-full text-button-uppercase py-2 text-center rounded-full duration-300 bg-white hover:bg-black hover:text-white cursor-pointer">
                    <span class="max-lg:hidden">Quick View</span>
                    <i class="ph ph-eye lg:hidden text-xl"></i>
                </div>
                <div class="add-cart-btn w-full text-button-uppercase py-2 text-center rounded-full duration-300 bg-white hover:bg-black hover:text-white cursor-pointer" data-id="{{ $product->id }}">
                    <span class="max-lg:hidden">Add To Cart</span>
                    <i class="ph ph-shopping-bag-open lg:hidden text-xl"></i>
                </div>
            </div>
            
        </div>
        <div class="product-infor mt-4 lg:mb-7 text-center">
            @if($product->category)
            <div class="caption2 font-semibold text-secondary2 uppercase mt-1 mb-1">
                {{ $product->category->name }}
            </div>
            @endif
            
            <a href="{{ route('frontend.product.details', ['slug' => $product->slug ?? $product->id]) }}" class="product-name text-title duration-300">{{ $product->name }}</a>
            
            @if($product->variations && $product->variations->pluck('color')->filter()->unique('id')->count() > 0)
            <div class="list-color py-2 max-md:hidden flex items-center justify-center gap-3 flex-wrap duration-500">
                @foreach($product->variations->pluck('color')->filter()->unique('id') as $color)
                <div class="color-item w-8 h-8 rounded-full duration-300 relative" style="background-color: {{ $color->code ?? $color->name }}; border: 1px solid #e1e1e1;">
                    <div class="tag-action bg-black text-white caption2 capitalize px-1.5 py-0.5 rounded-sm">{{ $color->name }}</div>
                </div>
                @endforeach
            </div>
            @endif
            
            <div class="product-price-block flex items-center justify-center gap-2 flex-wrap mt-1 duration-300 relative z-[1]">
                <div class="product-price text-title">৳{{ $product->sale_price }}</div>
                @if($product->purchase_price > $product->sale_price)
                <div class="product-origin-price caption1 text-secondary2">
                    <del>৳{{ $product->purchase_price }}</del>
                </div>
                <div class="product-sale caption1 font-medium bg-green px-3 py-0.5 inline-block rounded-full">-{{ round((($product->purchase_price - $product->sale_price) / $product->purchase_price) * 100) }}%</div>
                @endif
            </div>
        </div>
    </div>
</div>


