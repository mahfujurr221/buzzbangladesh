@if(count($cart) > 0)
    @foreach($cart as $key => $item)
        @php
            $imageUrl = $item['image']
                ? asset($item['image'])
                : asset('backend/images/products/placeholder.png');
        @endphp
        <div class="item flex items-center justify-between gap-3 pb-5 border-b border-line mb-5">
            <a href="{{ route('frontend.product.details', $item['slug']) }}" class="bg-img w-20 aspect-square flex-shrink-0 rounded-lg overflow-hidden">
                <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" />
            </a>
            <div class="infor flex-grow">
                <div class="name">
                    <a href="{{ route('frontend.product.details', $item['slug']) }}" class="text-title">{{ $item['name'] }}</a>
                </div>
                <div class="flex items-center gap-2 mt-1 text-secondary caption1">
                    @if($item['color_name'])
                        <span class="color">{{ $item['color_name'] }}</span>
                    @endif
                    @if($item['color_name'] && $item['size_name'])
                        <span>/</span>
                    @endif
                    @if($item['size_name'])
                        <span class="size">{{ $item['size_name'] }}</span>
                    @endif
                </div>
                <div class="text-title mt-2">৳{{ number_format($item['price'], 2) }}</div>
            </div>
            <div class="flex flex-col gap-2 items-end">
                <div class="quantity-block py-1 px-1 flex items-center justify-between rounded-full border border-gray-200 w-28 bg-gray-50 shadow-sm">
                    <i class="ph-bold ph-minus text-sm w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm transition-colors duration-300 cursor-pointer update-cart-btn"
                       data-key="{{ $key }}" data-qty="{{ $item['quantity'] - 1 }}"></i>
                    <div class="quantity font-semibold text-sm">{{ $item['quantity'] }}</div>
                    <i class="ph-bold ph-plus text-sm w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm transition-colors duration-300 cursor-pointer update-cart-btn"
                       data-key="{{ $key }}" data-qty="{{ $item['quantity'] + 1 }}"></i>
                </div>
                <div class="remove-cart-btn caption1 text-red underline cursor-pointer" data-key="{{ $key }}">Remove</div>
            </div>
        </div>
    @endforeach
@else
    <div class="text-center py-10">
        <div class="text-5xl mb-4">🛒</div>
        <div class="text-secondary mb-4">Your cart is empty.</div>
        <a href="{{ route('frontend.shop') }}" class="button-main">Shop Now</a>
    </div>
@endif
