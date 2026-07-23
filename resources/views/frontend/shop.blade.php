@extends('frontend.layouts.master')

@php $isHotDeals = $isHotDeals ?? false; @endphp

@push('styles')
<style>
    /* Shop Filter Theme: #9A0002 */
    .shop-cat-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 22px;
        border-radius: 999px;
        border: 0.5px solid #9A0002;
        background: #fff;
        color: #9A0002;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }
    .shop-cat-pill:hover, .shop-cat-pill.active {
        background: #9A0002;
        color: #fff;
    }

    /* Sidebar checkboxes */
    .shop-checkbox {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        border: 0.5px solid #9A0002;
        border-radius: 3px;
        background: #fff;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
        position: relative;
        transition: background 0.2s;
    }
    .shop-checkbox:checked {
        background: #9A0002;
        border-color: #9A0002;
    }
    .shop-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 1px;
        width: 5px;
        height: 8px;
        border: 2px solid #fff;
        border-top: none;
        border-left: none;
        transform: rotate(45deg);
    }
    .sidebar-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 5px 0;
        width: 100%;
    }
    .sidebar-label:hover .sidebar-label-text {
        color: #9A0002;
    }
    .sidebar-label-text {
        transition: color 0.2s;
    }
    .sidebar-label-text.selected {
        color: #9A0002;
        font-weight: 600;
    }
    .sidebar-count {
        font-size: 13px;
        color: #999;
        margin-left: 4px;
    }
    .sidebar-count.selected {
        color: #9A0002;
    }

    /* Size buttons */
    .size-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 14px;
        border-radius: 5px;
        border: 0.5px solid #9A0002;
        background: #fff;
        color: #9A0002;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .size-btn:hover, .size-btn.active {
        background: #9A0002;
        color: #fff;
    }

    /* Color items */
    .color-item-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 10px;
        border-radius: 8px;
        border: 0.5px solid #e5e5e5;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .color-item-row:hover, .color-item-row.active {
        border-color: #9A0002;
    }
    .color-item-row.active .color-item-name {
        color: #9A0002;
        font-weight: 600;
    }

    /* Price & Apply button */
    .btn-theme {
        width: 100%;
        background: #9A0002;
        color: #fff;
        border: none;
        padding: 8px 0;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-theme:hover {
        background: #7a0001;
    }

    /* Clear filter link */
    .clear-filter-link {
        font-size: 12px;
        color: #9A0002;
        display: inline-block;
        margin-top: 8px;
        text-decoration: none;
    }
    .clear-filter-link:hover {
        text-decoration: underline;
    }
    /* Category Scroll Buttons */
    .cat-scroll-btn {
        width: 36px;
        height: 36px;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        color: #9A0002;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .cat-scroll-btn:hover {
        background: #9A0002;
        color: #fff;
        border-color: #9A0002;
    }
    
    @media (max-width: 768px) {
        .cat-scroll-btn {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-linear overflow-hidden relative">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">Shop</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">Shop</div>
                    </div>
                </div>
                <div class="category-slider-wrap flex items-center justify-center gap-4 w-full lg:mt-[70px] mt-12 max-w-[1000px] mx-auto px-4">
                    <button class="cat-scroll-btn left" id="scroll-cat-left" style="margin-top: -2px;">
                        <i class="ph ph-caret-left"></i>
                    </button>
                    <div class="filter-type menu-tab flex items-center gap-4 overflow-x-auto flex-nowrap hide-scrollbar scroll-smooth flex-grow" id="top-cat-container" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <style>
                            .hide-scrollbar::-webkit-scrollbar {
                                display: none;
                            }
                        </style>
                        @php $selectedCategoriesTop = (array) request('category', []); @endphp
                        @if(!empty($hasActiveDeals))
                        <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}" 
                           class="shop-cat-pill flex-shrink-0 font-bold {{ $isHotDeals ? 'active' : '' }}"
                           style="{{ $isHotDeals ? 'background:#9A0002;color:#fff;' : 'border-color:#9A0002;color:#9A0002;' }}">
                            🔥 Hot Deals
                        </a>
                        @endif
                        <a href="{{ route('frontend.shop', collect(request()->query())->except('category')->except('filter')->toArray()) }}" 
                           class="shop-cat-pill flex-shrink-0 {{ empty($selectedCategoriesTop) && !$isHotDeals ? 'active' : '' }}">
                            All
                        </a>
                        @foreach($shopCategories as $category)
                            @php
                                $catParams = collect(request()->query())->except('category')->toArray();
                                $catParams['category'][] = $category->slug;
                            @endphp
                            <a href="{{ route('frontend.shop', $catParams) }}" 
                               class="shop-cat-pill flex-shrink-0 {{ in_array($category->slug, $selectedCategoriesTop) ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                    <button class="cat-scroll-btn right" id="scroll-cat-right" style="margin-top: -2px;">
                        <i class="ph ph-caret-right"></i>
                    </button>
                </div>
            </div>
            <div class="bg-img absolute top-0 right-0 w-full h-full z-[0] opacity-20">
                {{-- <img src="{{ isset($setting->shop_bg) && $setting->shop_bg ? asset($setting->shop_bg) : asset('frontend/images/slider/bg1-1.png') }}" alt="Shop Background" class="w-full h-full object-cover" /> --}}
            </div>
        </div>
    </div>
</div>

<form id="shop-filter-form" action="{{ route('frontend.shop') }}" method="GET">
    <div class="shop-product breadcrumb1 lg:py-20 md:py-14 py-10">
        <div class="container">
            <div class="flex max-md:flex-wrap max-md:flex-col-reverse gap-y-8">
                <div class="sidebar lg:w-1/4 md:w-1/3 w-full md:pr-12">
                    
                    {{-- Categories Sidebar --}}
                    <div class="filter-type-block pb-8 border-b border-line">
                        <div class="heading6">Products Type</div>
                        <div class="list-type filter-type menu-tab mt-4">
                            @php $selectedCategories = (array) request('category', []); @endphp
                            @if(!empty($hasActiveDeals))
                            <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}" 
                               class="sidebar-label text-decoration-none" style="margin-bottom: 6px;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span class="sidebar-label-text font-bold {{ $isHotDeals ? 'selected' : '' }}" style="color:#9A0002;">
                                        🔥 Hot Deals
                                    </span>
                                </div>
                            </a>
                            @endif
                            @foreach($shopCategories as $category)
                            <label class="sidebar-label">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <input type="checkbox" name="category[]" value="{{ $category->slug }}"
                                        class="shop-checkbox"
                                        {{ in_array($category->slug, $selectedCategories) ? 'checked' : '' }}>
                                    <span class="sidebar-label-text {{ in_array($category->slug, $selectedCategories) ? 'selected' : '' }}">
                                        {{ $category->name }}
                                        <span class="sidebar-count {{ in_array($category->slug, $selectedCategories) ? 'selected' : '' }}">({{ $category->products_count ?? 0 }})</span>
                                    </span>
                                </div>
                            </label>
                            @endforeach
                            @if(request('category'))
                            <a href="{{ route('frontend.shop', collect(request()->query())->except('category')->toArray()) }}" class="clear-filter-link">&times; Clear Category Filter</a>
                            @endif
                        </div>
                    </div>

                    {{-- Sizes Sidebar --}}
                    <div class="filter-size pb-8 border-b border-line mt-8">
                        <div class="heading6">Size</div>
                        <div class="list-size flex items-center flex-wrap gap-3 gap-y-4 mt-4">
                            @php $selectedSizes = (array) request('size', []); @endphp
                            @foreach($sizes as $size)
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="size[]" value="{{ $size->name }}" class="hidden" 
                                        {{ in_array($size->name, $selectedSizes) ? 'checked' : '' }}>
                                    <div class="size-btn {{ in_array($size->name, $selectedSizes) ? 'active' : '' }}">
                                        {{ $size->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Sidebar --}}
                    <div class="filter-price pb-8 border-b border-line mt-8">
                        <div class="heading6">Price Range</div>
                        <div class="price-inputs flex items-center gap-2 mt-4">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full border border-line rounded px-2 py-1 text-sm">
                            <span>-</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full border border-line rounded px-2 py-1 text-sm">
                        </div>
                        <button type="submit" class="btn-theme" style="margin-top:10px;">Apply Price</button>
                    </div>

                    {{-- Colors Sidebar --}}
                    <div class="filter-color pb-8 border-b border-line mt-8">
                        <div class="heading6">Colors</div>
                        <div class="list-color grid grid-cols-2 gap-2 mt-4">
                            @php $selectedColors = (array) request('color', []); @endphp
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px;">
                            @foreach($colors as $color)
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="color[]" value="{{ $color->name }}" class="hidden" 
                                        {{ in_array($color->name, $selectedColors) ? 'checked' : '' }}>
                                    <div class="color-item-row {{ in_array($color->name, $selectedColors) ? 'active' : '' }}">
                                        <div style="width:14px;height:14px;border-radius:50%;flex-shrink:0;background-color:{{ $color->code }};border:1px solid #ddd;"></div>
                                        <span class="color-item-name" style="font-size:12px;text-transform:capitalize;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $color->name }}</span>
                                        @if(in_array($color->name, $selectedColors))
                                            <span style="margin-left:auto;color:#9A0002;font-size:10px;">✓</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                            </div>
                        </div>
                    </div>


                </div>

                {{-- Product List Block --}}
                <div class="list-product-block style-grid lg:w-3/4 md:w-2/3 w-full md:pl-3">
                    <div class="filter-heading flex items-center justify-between gap-5 flex-wrap">
                        <div class="left flex has-line items-center flex-wrap gap-5">
                            <div class="choose-layout menu-tab flex items-center gap-2">
                                <div class="item tab-item style-grid three-col p-2 border border-[#9A0002] bg-[#9A0002] rounded flex items-center justify-center cursor-pointer active">
                                    <div class="flex items-center gap-0.5">
                                        <span class="w-[3px] h-4 bg-white rounded-sm"></span>
                                        <span class="w-[3px] h-4 bg-white rounded-sm"></span>
                                        <span class="w-[3px] h-4 bg-white rounded-sm"></span>
                                    </div>
                                </div>
                            </div>
                            <label class="check-sale flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="on_sale" value="1" class="border-line" 
                                    {{ request('on_sale') ? 'checked' : '' }} />
                                <span class="cation1 cursor-pointer">Show only products on sale</span>
                            </label>
                        </div>
                        <div class="sort-product right flex items-center gap-3">
                            <label for="select-filter" class="caption1 capitalize">Sort by</label>
                            <div class="select-block relative">
                                <select id="select-filter" name="sort" class="caption1 py-2 pl-3 md:pr-20 pr-10 rounded-lg border border-line">
                                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Latest</option>
                                    <option value="soldQuantityHighToLow" {{ request('sort') == 'soldQuantityHighToLow' ? 'selected' : '' }}>Best Selling</option>
                                    <option value="discountHighToLow" {{ request('sort') == 'discountHighToLow' ? 'selected' : '' }}>Best Discount</option>
                                    <option value="priceHighToLow" {{ request('sort') == 'priceHighToLow' ? 'selected' : '' }}>Price High To Low</option>
                                    <option value="priceLowToHigh" {{ request('sort') == 'priceLowToHigh' ? 'selected' : '' }}>Price Low To High</option>
                                </select>
                                <i class="ph ph-caret-down absolute top-1/2 -translate-y-1/2 md:right-4 right-2"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Products --}}
                    <div class="list-product grid lg:grid-cols-3 grid-cols-2 sm:gap-[30px] gap-[20px] mt-7">
                        @forelse($products as $product)
                            @include('frontend.partials.product-item', ['product' => $product])
                        @empty
                            <div class="col-span-full py-10 text-center text-gray-500">
                                No products found matching your criteria.
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="list-pagination w-full flex items-center gap-4 mt-10 justify-center">
                        {{ $products->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle for mobile
            const filterSidebarBtn = document.querySelector('.filter-sidebar-btn');
            const sidebar = document.querySelector('.sidebar');
            if (filterSidebarBtn && sidebar) {
                filterSidebarBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                });
            }

            const form = document.getElementById('shop-filter-form');
            const productBlock = document.querySelector('.list-product-block');
            const sidebarBlock = document.querySelector('.sidebar');

            // Function to fetch and update DOM
            function updateShop(url) {
                // Visual feedback
                productBlock.style.opacity = '0.5';
                
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Replace products and sidebar (to update counts & active states)
                    const newProductBlock = doc.querySelector('.list-product-block');
                    const newSidebarBlock = doc.querySelector('.sidebar');
                    const newTopFilter = doc.querySelector('.breadcrumb-main .filter-type');
                    
                    if (newProductBlock) productBlock.innerHTML = newProductBlock.innerHTML;
                    if (newSidebarBlock) sidebarBlock.innerHTML = newSidebarBlock.innerHTML;
                    if (newTopFilter) {
                        const topFilter = document.querySelector('.breadcrumb-main .filter-type');
                        if (topFilter) topFilter.innerHTML = newTopFilter.innerHTML;
                    }

                    productBlock.style.opacity = '1';

                    // Update URL
                    history.pushState(null, '', url);

                    // Re-bind cart buttons (cart.js uses MutationObserver, but if we need manual init we could call it here)
                })
                .catch(error => {
                    console.error('Error fetching shop data:', error);
                    productBlock.style.opacity = '1';
                });
            }

            // Intercept form changes
            form.addEventListener('change', function(e) {
                const url = new URL(form.action);
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                url.search = params.toString();
                updateShop(url.toString());
            });

            // Intercept form submit (e.g. hitting enter in price input or clicking 'Apply Price')
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = new URL(form.action);
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                url.search = params.toString();
                updateShop(url.toString());
            });

            // Intercept pagination clicks only — top category pills navigate directly
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.list-pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    updateShop(paginationLink.href);
                    document.querySelector('.shop-product').scrollIntoView({ behavior: 'smooth' });
                }
            });
            
            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                updateShop(window.location.href);
            });

            // Top Category Scroll
            const catContainer = document.getElementById('top-cat-container');
            const scrollLeftBtn = document.getElementById('scroll-cat-left');
            const scrollRightBtn = document.getElementById('scroll-cat-right');

            if (catContainer && scrollLeftBtn && scrollRightBtn) {
                const scrollAmount = 300;
                scrollLeftBtn.addEventListener('click', () => {
                    catContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });
                scrollRightBtn.addEventListener('click', () => {
                    catContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });
                
                // Show/hide buttons based on scroll position
                const checkScroll = () => {
                    scrollLeftBtn.style.opacity = catContainer.scrollLeft > 0 ? '1' : '0.5';
                    scrollLeftBtn.style.pointerEvents = catContainer.scrollLeft > 0 ? 'auto' : 'none';
                    
                    const maxScroll = catContainer.scrollWidth - catContainer.clientWidth;
                    scrollRightBtn.style.opacity = catContainer.scrollLeft >= maxScroll - 1 ? '0.5' : '1';
                    scrollRightBtn.style.pointerEvents = catContainer.scrollLeft >= maxScroll - 1 ? 'none' : 'auto';
                };
                
                catContainer.addEventListener('scroll', checkScroll);
                window.addEventListener('resize', checkScroll);
                // Initial check
                setTimeout(checkScroll, 100);
            }
        });
    </script>
@endpush
@endsection