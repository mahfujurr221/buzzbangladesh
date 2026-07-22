@push('styles')
<style>
    .modal-cart-block .list-product {
        max-height: calc(100vh - 240px);
        overflow-y: auto;
    }
    .modal-cart-block .list-product::-webkit-scrollbar { width: 6px; }
    .modal-cart-block .list-product::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
    
    .modal-cart-block .update-cart-btn {
        color: #9A0002;
    }
    .modal-cart-block .update-cart-btn:hover {
        background-color: #9A0002;
        color: white;
    }
</style>
@endpush

<div class="modal-search-block" style="z-index: 9999;">
    <div class="modal-search-main bg-white w-[90%] max-w-[750px] shadow-2xl rounded-[32px] absolute top-[15vh] left-1/2 -translate-x-1/2 transition-all duration-300" style="overflow: visible;">
        <div class="p-6 md:p-10">
            <!-- Search Header -->
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl md:text-3xl font-bold text-gray-800" style="margin-right: 1rem;">What are you looking for?</h2>
                <div class="close-btn rounded-full flex items-center justify-center cursor-pointer hover:bg-black hover:text-white transition-colors" style="width: 2.5rem; height: 2.5rem; background-color: #f3f4f6; flex-shrink: 0;">
                    <i class="ph ph-x text-lg"></i>
                </div>
            </div>
            
            <div class="relative w-full">
                <form action="{{ route('frontend.shop') }}" method="GET" class="relative flex items-center">
                    <i class="ph ph-magnifying-glass absolute text-2xl text-gray-400 pointer-events-none" style="left: 1.25rem;"></i>
                    <input type="text" name="q" id="search-input-modal" placeholder="Search products..." value="{{ request('q') }}" class="w-full bg-gray-50 rounded-full py-5 text-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-black transition-all border border-transparent focus:bg-white" autocomplete="off" style="padding-left: 3.5rem; padding-right: 1.5rem;">
                    <button type="submit" class="hidden"></button>
                </form>
                
                <div id="search-suggestions" class="absolute left-0 right-0 bg-white shadow-2xl mt-3 rounded-[24px] hidden overflow-hidden z-[9999] border border-gray-100 max-h-[50vh] overflow-y-auto"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input-modal');
    const suggestionsBox = document.getElementById('search-suggestions');
    let timeoutId;

    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsBox.classList.add('hidden');
                return;
            }

            timeoutId = setTimeout(() => {
                fetch(`{{ route('frontend.search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(product => {
                                const item = document.createElement('a');
                                item.href = product.url;
                                item.style.cssText = 'display: flex; align-items: center; gap: 1rem; padding: 1rem; text-decoration: none; border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;';
                                item.onmouseover = () => item.style.backgroundColor = '#f9fafb';
                                item.onmouseout = () => item.style.backgroundColor = 'transparent';
                                
                                item.innerHTML = `
                                    <div style="width: 3.5rem; height: 3.5rem; background-color: #f3f4f6; border-radius: 12px; overflow: hidden; flex-shrink: 0;">
                                        <img src="${product.image}" alt="${product.name}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <div style="font-size: 1rem; font-weight: 500; color: #1f2937; margin-bottom: 0.25rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">${product.name}</div>
                                        <div style="font-size: 1rem; font-weight: 700; color: #9A0002;">${product.price}</div>
                                    </div>
                                `;
                                suggestionsBox.appendChild(item);
                            });
                            suggestionsBox.classList.remove('hidden');
                        } else {
                            suggestionsBox.innerHTML = '<div style="padding: 1.5rem; font-size: 0.875rem; color: #6b7280; text-align: center;">No products found</div>';
                            suggestionsBox.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                    });
            }, 300); // 300ms debounce
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('hidden');
            }
        });
        
        // Close modal when close button is clicked
        const closeBtn = document.querySelector('.modal-search-block .close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                const modalSearchMain = document.querySelector('.modal-search-block .modal-search-main');
                if (modalSearchMain) {
                    modalSearchMain.classList.remove('open');
                }
            });
        }
    }
});
</script>
@endpush


<div class="modal-wishlist-block">
            <div class="modal-wishlist-main py-6">
                <div class="heading px-6 pb-3 flex items-center justify-between relative">
                    <div class="heading5">Wishlist</div>
                    <div class="close-btn absolute right-6 top-0 w-6 h-6 rounded-full bg-surface flex items-center justify-center duration-300 cursor-pointer hover:bg-black hover:text-white">
                        <i class="ph ph-x text-sm"></i>
                    </div>
                </div>
                <div class="list-product px-6"></div>
                <div class="footer-modal p-6 border-t bg-white border-line absolute bottom-0 left-0 w-full text-center">
                    <a href="wishlist.html" class="button-main w-full text-center uppercase"> View All Wish List</a>
                    <div class="text-button-uppercase continue mt-4 text-center has-line-before cursor-pointer inline-block">Or continue shopping</div>
                </div>
            </div>
        </div>

        <div class="modal-cart-block">
            <div class="modal-cart-main flex">
                <div class="left w-1/2 border-r border-line py-6 max-md:hidden">
                    <div class="heading5 px-6 pb-3">You May Also Like</div>
                    <div class="list px-6" style="max-height: 100vh; overflow-y: auto;">
                        @if(isset($cartRecommendedProducts) && $cartRecommendedProducts->count() > 0)
                            @foreach($cartRecommendedProducts as $product)
                                @php
                                    $img = $product->images->first();
                                    $imageUrl = $img ? asset($img->image_path) : asset('backend/images/products/placeholder.png');
                                @endphp
                                <div class="item flex items-center gap-3 pb-5 border-b border-line mb-5">
                                    <a href="{{ route('frontend.product.details', $product->slug) }}" class="bg-img w-20 aspect-square flex-shrink-0 rounded-lg overflow-hidden">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                    </a>
                                    <div class="infor flex-grow">
                                        <div class="name">
                                            <a href="{{ route('frontend.product.details', $product->slug) }}" class="text-title line-clamp-2">{{ $product->name }}</a>
                                        </div>
                                        <div class="text-title mt-2 text-[#9A0002]">৳{{ number_format($product->sale_price ?? $product->purchase_price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="caption1 text-secondary">No recommendations available.</div>
                        @endif
                    </div>
                </div>
                <div class="right cart-block md:w-1/2 w-full py-6 relative overflow-hidden">
                    <div class="heading px-6 pb-3 flex items-center justify-between relative">
                        <div class="heading5">Shopping Cart</div>
                        <div class="close-btn absolute right-6 top-0 w-6 h-6 rounded-full bg-surface flex items-center justify-center duration-300 cursor-pointer hover:bg-black hover:text-white">
                            <i class="ph ph-x text-sm"></i>
                        </div>
                    </div>

                    <div class="list-product px-6"></div>
                    <div class="footer-modal bg-white absolute bottom-0 left-0 w-full">
                        {{--
                        <div class="flex items-center justify-center lg:gap-14 gap-8 px-6 py-4 border-b border-line">
                            <div class="note-btn item flex items-center gap-3 cursor-pointer">
                                <i class="ph ph-note-pencil text-xl"></i>
                                <div class="caption1">Note</div>
                            </div>
                            <div class="shipping-btn item flex items-center gap-3 cursor-pointer">
                                <i class="ph ph-truck text-xl"></i>
                                <div class="caption1">Shipping</div>
                            </div>
                            <div class="coupon-btn item flex items-center gap-3 cursor-pointer">
                                <i class="ph ph-tag text-xl"></i>
                                <div class="caption1">Coupon</div>
                            </div>
                        </div>
                        --}}
                        <div class="flex items-center justify-between pt-6 px-6">
                            <div class="heading5">Subtotal</div>
                            <div class="heading5 total-cart">$0.00</div>
                        </div>
                        <div class="block-button text-center p-6">
                            <div class="flex items-center gap-4">
                                <a href="/checkout" class="button-main w-full text-center uppercase" style="background:#9A0002;color:white;border-color:#9A0002;"> Check Out </a>
                            </div>
                            <div class="text-button-uppercase continue mt-4 text-center has-line-before cursor-pointer inline-block">Or continue shopping</div>
                        </div>
                        {{--
                        <div class="tab-item note-block">
                            <div class="px-6 py-4 border-b border-line">
                                <div class="item flex items-center gap-3 cursor-pointer">
                                    <i class="ph ph-note-pencil text-xl"></i>
                                    <div class="caption1">Note</div>
                                </div>
                            </div>
                            <div class="form pt-4 px-6">
                                <textarea name="form-note" id="form-note" rows="4" placeholder="Add special instructions for your order..." class="caption1 py-3 px-4 bg-surface border-line rounded-md w-full"></textarea>
                            </div>
                            <div class="block-button text-center pt-4 px-6 pb-6">
                                <div class="button-main w-full text-center">Save</div>
                                <div class="cancel-btn text-button-uppercase mt-4 text-center has-line-before cursor-pointer inline-block">Cancel</div>
                            </div>
                        </div>
                        <div class="tab-item shipping-block">
                            <div class="px-6 py-4 border-b border-line">
                                <div class="item flex items-center gap-3 cursor-pointer">
                                    <i class="ph ph-truck text-xl"></i>
                                    <div class="caption1">Estimate shipping rates</div>
                                </div>
                            </div>
                            <div class="form pt-4 px-6">
                                <div class="">
                                    <label for="select-country" class="caption1 text-secondary">Country/region</label>
                                    <div class="select-block relative mt-2">
                                        <select id="select-country" name="select-country" class="w-full py-3 pl-5 rounded-xl bg-white border border-line">
                                            <option value="Country/region">Country/region</option>
                                            <option value="France">France</option>
                                            <option value="Spain">Spain</option>
                                            <option value="UK">UK</option>
                                            <option value="USA">USA</option>
                                        </select>
                                        <i class="ph ph-caret-down text-xs absolute top-1/2 -translate-y-1/2 md:right-5 right-2"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="select-state" class="caption1 text-secondary">State</label>
                                    <div class="select-block relative mt-2">
                                        <select id="select-state" name="select-state" class="w-full py-3 pl-5 rounded-xl bg-white border border-line">
                                            <option value="State">State</option>
                                            <option value="Paris">Paris</option>
                                            <option value="Madrid">Madrid</option>
                                            <option value="London">London</option>
                                            <option value="New York">New York</option>
                                        </select>
                                        <i class="ph ph-caret-down text-xs absolute top-1/2 -translate-y-1/2 md:right-5 right-2"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="select-code" class="caption1 text-secondary">Postal/Zip Code</label>
                                    <input class="border-line px-5 py-3 w-full rounded-xl mt-3" id="select-code" type="text" placeholder="Postal/Zip Code" />
                                </div>
                            </div>
                            <div class="block-button text-center pt-4 px-6 pb-6">
                                <div class="button-main w-full text-center">Calculator</div>
                                <div class="cancel-btn text-button-uppercase mt-4 text-center has-line-before cursor-pointer inline-block">Cancel</div>
                            </div>
                        </div>
                        <div class="tab-item coupon-block">
                            <div class="px-6 py-4 border-b border-line">
                                <div class="item flex items-center gap-3 cursor-pointer">
                                    <i class="ph ph-tag text-xl"></i>
                                    <div class="caption1">Add A Coupon Code</div>
                                </div>
                            </div>
                            <div class="form pt-4 px-6">
                                <div class="">
                                    <label for="select-discount" class="caption1 text-secondary">Enter Code</label>
                                    <input class="border-line px-5 py-3 w-full rounded-xl mt-3" id="select-discount" type="text" placeholder="Discount code" />
                                </div>
                            </div>
                            <div class="block-button text-center pt-4 px-6 pb-6">
                                <div class="button-main w-full text-center">Apply</div>
                                <div class="cancel-btn text-button-uppercase mt-4 text-center has-line-before cursor-pointer inline-block">Cancel</div>
                            </div>
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-compare-block">
            <div class="modal-compare-main py-6">
                <div class="close-btn absolute 2xl:right-6 right-4 2xl:top-6 md:-top-4 top-3 lg:w-10 w-6 lg:h-10 h-6 rounded-full bg-surface flex items-center justify-center duration-300 cursor-pointer hover:bg-black hover:text-white">
                    <i class="ph ph-x body1"></i>
                </div>
                <div class="container h-full flex items-center w-full">
                    <div class="content-main flex items-center justify-between xl:gap-10 gap-6 w-full max-md:flex-wrap">
                        <div class="heading5 flex-shrink-0 max-md:w-full">Compare <br class="max-md:hidden" />Products</div>
                        <div class="list-product flex items-center w-full gap-4"></div>
                        <div class="block-button flex flex-col gap-4 flex-shrink-0">
                            <a href="/compare" class="button-main whitespace-nowrap"> Compare Products</a>
                            <div class="button-main clear whitespace-nowrap border border-black bg-white text-black">Clear All Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
