@extends('frontend.layouts.master')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">Checkout</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">Checkout</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Checkout Block --}}
<div class="checkout-block md:py-20 py-10">
    <div class="container">

        {{-- Empty cart guard --}}
        <div id="empty-cart-msg" class="hidden text-center py-20">
            <div class="text-5xl mb-4">🛒</div>
            <div class="heading4 mb-2">Your cart is empty</div>
            <p class="text-secondary mb-6">Add some products before checking out.</p>
            <a href="{{ route('frontend.shop') }}" class="button-main">Continue Shopping</a>
        </div>

        {{-- Checkout content (shown when cart has items) --}}
        <div id="checkout-content" class="content-main flex max-lg:flex-col-reverse gap-y-10 justify-between">

            {{-- LEFT: Customer Information Form --}}
            <div class="left lg:w-1/2">
                <form id="checkout-form" novalidate>
                    @csrf

                    <div class="information">
                        <div class="heading5 mb-1">Delivery Information</div>
                        <p class="text-secondary caption1 mb-5">Fill in your details to complete the order.</p>

                        <div class="grid sm:grid-cols-2 gap-4 gap-y-5 flex-wrap">
                            {{-- Full Name --}}
                            <div class="col-span-full">
                                <label for="co-name" class="caption1 text-secondary mb-1 block">Full Name <span class="text-red">*</span></label>
                                <input class="border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                       id="co-name" name="name" type="text" placeholder="e.g. Mahfuz Rahman" required />
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="co-phone" class="caption1 text-secondary mb-1 block">Phone Number <span class="text-red">*</span></label>
                                <input class="border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                       id="co-phone" name="phone" type="tel" placeholder="e.g. 01XXXXXXXXX" required />
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="co-email" class="caption1 text-secondary mb-1 block">Email Address <span class="text-secondary2">(optional)</span></label>
                                <input class="border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                       id="co-email" name="email" type="email" placeholder="e.g. you@email.com" />
                            </div>

                            {{-- City --}}
                            <div>
                                <label for="co-city" class="caption1 text-secondary mb-1 block">City <span class="text-red">*</span></label>
                                <input class="border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                       id="co-city" name="city" type="text" placeholder="e.g. Dhaka" required />
                            </div>

                            {{-- Thana --}}
                            <div>
                                <label for="co-thana" class="caption1 text-secondary mb-1 block">Thana / Upazila <span class="text-red">*</span></label>
                                <input class="border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                       id="co-thana" name="thana" type="text" placeholder="e.g. Mirpur" required />
                            </div>

                            {{-- Full Address --}}
                            <div class="col-span-full">
                                <label for="co-address" class="caption1 text-secondary mb-1 block">Full Address <span class="text-red">*</span></label>
                                <textarea class="border border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                          id="co-address" name="address" rows="3" placeholder="House no, Road no, Area..." required></textarea>
                            </div>

                            {{-- Order Notes --}}
                            <div class="col-span-full">
                                <label for="co-notes" class="caption1 text-secondary mb-1 block">Order Notes <span class="text-secondary2">(optional)</span></label>
                                <textarea class="border border-line px-4 py-3 w-full rounded-lg focus:outline-none focus:border-black transition-colors"
                                          id="co-notes" name="notes" rows="2" placeholder="Any special instructions for your order..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method: COD Only --}}
                    <div class="payment-block md:mt-8 mt-6">
                        <div class="heading5 mb-4">Payment Method</div>
                        <div class="type bg-surface p-5 border-2 border-black rounded-lg flex items-start gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-black flex items-center justify-center flex-shrink-0 mt-0.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-black"></div>
                            </div>
                            <div>
                                <div class="text-button">Cash on Delivery (COD)</div>
                                <div class="text-secondary caption1 mt-1">Pay with cash when your order is delivered at your doorstep. No advance payment needed.</div>
                            </div>
                        </div>
                        <input type="hidden" name="payment_method" value="cod">
                    </div>

                    {{-- Error message --}}
                    <div id="checkout-error" class="hidden mt-4 p-4 bg-red-50 border border-red-200 text-red rounded-lg caption1"></div>

                    {{-- Submit Button --}}
                    <div class="block-button md:mt-8 mt-6">
                        <button type="submit" id="place-order-btn" class="button-main w-full flex items-center justify-center gap-2 text-center">
                            <span id="btn-text">Place Order</span>
                            <span id="btn-spinner" class="hidden">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 14 5.373 14 12h-4z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>

                </form>
            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="right lg:w-5/12">
                <div class="checkout-order-block sticky top-24">
                    <div class="heading5 pb-4 border-b border-line">Your Order</div>

                    {{-- Product List (loaded dynamically) --}}
                    <div id="checkout-items" class="mt-4 space-y-4">
                        {{-- JS will populate this --}}
                        <div class="text-center py-8 text-secondary">
                            <svg class="animate-spin h-6 w-6 mx-auto mb-2 text-secondary2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 14 5.373 14 12h-4z"></path>
                            </svg>
                            Loading cart...
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="mt-4 pt-4 border-t border-line space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="text-secondary">Subtotal</div>
                            <div class="text-title checkout-subtotal">৳0.00</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-secondary">Shipping</div>
                            <div class="text-title text-green font-medium">Free</div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-line">
                            <div class="heading5">Total</div>
                            <div class="heading5 checkout-total">৳0.00</div>
                        </div>
                    </div>

                    {{-- COD badge --}}
                    <div class="mt-5 flex items-center gap-2 p-3 bg-surface rounded-lg">
                        <i class="ph ph-shield-check text-green text-xl flex-shrink-0"></i>
                        <div class="caption1 text-secondary">Cash on Delivery — Pay when you receive your order.</div>
                    </div>
                </div>
            </div>

        </div>{{-- end #checkout-content --}}
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const checkoutContent = document.getElementById('checkout-content');
    const emptyCartMsg    = document.getElementById('empty-cart-msg');
    const itemsContainer  = document.getElementById('checkout-items');
    const subtotalEl      = document.querySelector('.checkout-subtotal');
    const totalEl         = document.querySelector('.checkout-total');
    const errorEl         = document.getElementById('checkout-error');
    const form            = document.getElementById('checkout-form');
    const placeBtn        = document.getElementById('place-order-btn');
    const btnText         = document.getElementById('btn-text');
    const btnSpinner      = document.getElementById('btn-spinner');

    // ── Load cart data from server ─────────────────────────────────────────
    function loadCartData() {
        fetch('{{ route("frontend.cart.data") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.count || data.count === 0) {
                checkoutContent.classList.add('hidden');
                emptyCartMsg.classList.remove('hidden');
                return;
            }

            renderItems(data.items);
            const fmt = n => '৳' + Number(n).toLocaleString('en-BD', { minimumFractionDigits: 2 });
            subtotalEl.textContent = fmt(data.total);
            totalEl.textContent    = fmt(data.total);
        })
        .catch(() => {
            itemsContainer.innerHTML = '<p class="text-red caption1">Could not load cart. Please refresh.</p>';
        });
    }

    function renderItems(items) {
        itemsContainer.innerHTML = items.map(item => `
            <div class="flex items-center gap-3 py-3 border-b border-line last:border-0">
                <a href="/product/${item.slug}" class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-surface">
                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover" />
                </a>
                <div class="flex-grow min-w-0">
                    <div class="text-title text-sm font-medium truncate">${item.name}</div>
                    <div class="caption1 text-secondary mt-0.5">
                        ${[item.color_name, item.size_name].filter(Boolean).join(' / ')}
                    </div>
                    <div class="caption1 text-secondary mt-0.5">Qty: ${item.quantity}</div>
                </div>
                <div class="text-title text-sm font-semibold flex-shrink-0">৳${Number(item.subtotal).toLocaleString('en-BD', { minimumFractionDigits: 2 })}</div>
            </div>
        `).join('');
    }

    // ── Handle form submission ─────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Simple client-side validation
        const required = ['co-name', 'co-phone', 'co-city', 'co-thana', 'co-address'];
        let valid = true;
        required.forEach(id => {
            const el = document.getElementById(id);
            if (!el.value.trim()) {
                el.classList.add('border-red');
                valid = false;
            } else {
                el.classList.remove('border-red');
            }
        });

        if (!valid) {
            showError('Please fill in all required fields.');
            return;
        }

        // Show spinner
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
        placeBtn.disabled = true;
        hideError();

        const formData = {
            _token:  csrfToken,
            name:    document.getElementById('co-name').value.trim(),
            phone:   document.getElementById('co-phone').value.trim(),
            email:   document.getElementById('co-email').value.trim(),
            city:    document.getElementById('co-city').value.trim(),
            thana:   document.getElementById('co-thana').value.trim(),
            address: document.getElementById('co-address').value.trim(),
            notes:   document.getElementById('co-notes').value.trim(),
            payment_method: 'cod',
        };

        fetch('{{ route("frontend.order.place") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(formData),
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = data.redirect;
            } else {
                showError(data.message || 'Something went wrong. Please try again.');
                resetBtn();
            }
        })
        .catch(() => {
            showError('Network error. Please check your connection and try again.');
            resetBtn();
        });
    });

    // Clear red border on input focus
    form.querySelectorAll('input, textarea').forEach(el => {
        el.addEventListener('focus', () => el.classList.remove('border-red'));
    });

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
        errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideError() {
        errorEl.classList.add('hidden');
    }

    function resetBtn() {
        btnText.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
        placeBtn.disabled = false;
    }

    loadCartData();
});
</script>
@endpush