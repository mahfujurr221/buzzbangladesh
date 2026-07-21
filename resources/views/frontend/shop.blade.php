@extends('frontend.layouts.master')

@section('content')
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-linear overflow-hidden">
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
                <div class="filter-type menu-tab flex flex-wrap items-center justify-center gap-y-5 gap-8 lg:mt-[70px] mt-12 overflow-hidden">
                    @foreach($categories->take(5) as $category)
                        <a href="{{ route('frontend.shop', array_merge(request()->query(), ['category' => $category->slug])) }}" 
                           class="item tab-item text-button-uppercase cursor-pointer has-line-before line-2px {{ request('category') == $category->slug ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="bg-img absolute top-2 -right-6 max-lg:bottom-0 max-lg:top-auto w-1/3 max-lg:w-[26%] z-[0] max-sm:w-[45%]">
                <img src="{{ asset('frontend/images/slider/bg1-1.png') }}" alt="img" class="" />
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
                            @foreach($categories as $category)
                            <label class="item tab-item flex items-center justify-between cursor-pointer w-full mb-2">
                                <div class="left flex items-center cursor-pointer">
                                    <input type="radio" name="category" value="{{ $category->slug }}" class="mr-2"
                                        {{ request('category') == $category->slug ? 'checked' : '' }}>
                                    <div class="type-name text-secondary has-line-before hover:text-black capitalize">{{ $category->name }}</div>
                                </div>
                                <div class="text-secondary2 number">{{ $category->products_count }}</div>
                            </label>
                            @endforeach
                            @if(request('category'))
                            <a href="{{ route('frontend.shop', collect(request()->query())->except('category')->toArray()) }}" class="text-xs text-red mt-2 inline-block">Clear Category Filter</a>
                            @endif
                        </div>
                    </div>

                    {{-- Sizes Sidebar --}}
                    <div class="filter-size pb-8 border-b border-line mt-8">
                        <div class="heading6">Size</div>
                        <div class="list-size flex items-center flex-wrap gap-3 gap-y-4 mt-4">
                            @php $selectedSizes = (array) request('size', []); @endphp
                            @foreach($sizes as $size)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="size[]" value="{{ $size->name }}" class="hidden" 
                                        {{ in_array($size->name, $selectedSizes) ? 'checked' : '' }}>
                                    <div class="size-item text-button px-4 py-2 flex items-center justify-center rounded border {{ in_array($size->name, $selectedSizes) ? 'bg-black text-white border-black' : 'border-line text-black' }}">
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
                        <button type="submit" class="mt-3 w-full bg-black text-white text-xs py-2 rounded">Apply Price</button>
                    </div>

                    {{-- Colors Sidebar --}}
                    <div class="filter-color pb-8 border-b border-line mt-8">
                        <div class="heading6">Colors</div>
                        <div class="list-color flex items-center flex-wrap gap-3 gap-y-4 mt-4">
                            @php $selectedColors = (array) request('color', []); @endphp
                            @foreach($colors as $color)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="color[]" value="{{ $color->name }}" class="hidden" 
                                        {{ in_array($color->name, $selectedColors) ? 'checked' : '' }}>
                                    <div class="color-item px-3 py-[5px] flex items-center justify-center gap-2 rounded-full border {{ in_array($color->name, $selectedColors) ? 'border-black' : 'border-line' }}">
                                        <div class="color w-5 h-5 rounded-full" style="background-color: {{ $color->code }}; border: 1px solid #ddd;"></div>
                                        <div class="caption1 capitalize">{{ $color->name }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brands Sidebar --}}
                    <div class="filter-brand pb-8 mt-8">
                        <div class="heading6">Brands</div>
                        <div class="list-brand mt-4">
                            @php $selectedBrands = (array) request('brands', []); @endphp
                            @foreach($brands as $brand)
                                <label class="brand-item flex items-center justify-between cursor-pointer mb-2 w-full">
                                    <div class="left flex items-center cursor-pointer">
                                        <input type="checkbox" name="brands[]" value="{{ $brand->slug }}" class="mr-2"
                                            {{ in_array($brand->slug, $selectedBrands) ? 'checked' : '' }}>
                                        <span class="brand-name capitalize pl-1 cursor-pointer">{{ $brand->name }}</span>
                                    </div>
                                    <div class="text-secondary2 number">{{ $brand->products_count }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Product List Block --}}
                <div class="list-product-block style-grid lg:w-3/4 md:w-2/3 w-full md:pl-3">
                    <div class="filter-heading flex items-center justify-between gap-5 flex-wrap">
                        <div class="left flex has-line items-center flex-wrap gap-5">
                            <div class="choose-layout menu-tab flex items-center gap-2">
                                <div class="item tab-item style-grid three-col p-2 border border-black bg-black rounded flex items-center justify-center cursor-pointer active">
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

            // Intercept pagination clicks
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.list-pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    updateShop(paginationLink.href);
                    document.querySelector('.shop-product').scrollIntoView({ behavior: 'smooth' });
                }
                
                const topCategoryLink = e.target.closest('.filter-type a.item');
                if (topCategoryLink) {
                    e.preventDefault();
                    
                    // We also need to update the radio button in the sidebar to match, 
                    // or just rely on the server returning the new checked state.
                    // Let's just fetch the URL directly:
                    updateShop(topCategoryLink.href);
                }
            });
            
            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                updateShop(window.location.href);
            });
        });
    </script>
@endpush
@endsection