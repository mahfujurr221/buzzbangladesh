@extends('frontend.layouts.master')

@push('styles')
<style>
    :root { --brand: #9A0002; --brand-dark: #6b0001; }

    /* ===== CHECKOUT PAGE ===== */
    .checkout-wrap {
        background: #f7f7f7;
        padding: 60px 0 80px;
    }

    /* ---- Step Header (Dynamic from Order Statuses) ---- */
    .checkout-steps-wrapper {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 12px;
        margin-bottom: 24px;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .checkout-steps-wrapper::-webkit-scrollbar {
        display: none;
    }
    .checkout-steps {
        display: inline-flex;
        align-items: center;
        gap: 0;
        min-width: max-content;
    }
    .step-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #aaa;
    }
    .step-item.active { color: var(--brand); }
    .step-item.done { color: #10b981; } /* Emerald-500 */
    .step-item.done .step-num {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    .step-item.done .step-icon { font-size: 16px; }
    .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px;
        font-weight: 700;
        border: 1.5px solid #ddd;
        background: #fff;
        color: #aaa;
        flex-shrink: 0;
    }
    .step-divider {
        width: 30px;
        height: 1.5px;
        background: #e0e0e0;
        margin: 0 12px;
    }

    /* ---- Cards ---- */
    .co-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #9A0002;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .co-card-header {
        padding: 20px 28px;
        border-bottom: 0.5px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .co-card-header-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: rgba(154,0,2,0.08);
        color: var(--brand);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .co-card-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: #111;
        margin: 0;
    }
    .co-card-header p {
        font-size: 12px;
        color: #999;
        margin: 2px 0 0;
    }
    .co-card-body { padding: 24px 28px; }

    /* ---- Form Fields ---- */
    .co-field-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        letter-spacing: 0.3px;
        margin-bottom: 7px;
    }
    .co-field-required { color: var(--brand); }
    .co-field-optional { color: #bbb; font-weight: 400; }
    .co-input {
        width: 100%;
        padding: 11px 14px;
        border: 0.5px solid #ddd;
        border-radius: 10px;
        font-size: 14px;
        color: #222;
        background: #fafafa;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }
    .co-input::placeholder { color: #c0c0c0; }
    .co-input:focus {
        border-color: var(--brand);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(154,0,2,0.07);
    }
    .co-input.error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08) !important;
    }
    .co-field-group { margin-bottom: 18px; }
    .co-fields-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    @media(max-width:580px){ .co-fields-row { grid-template-columns: 1fr; } }

    /* ---- Payment Block ---- */
    .payment-option {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px;
        border: 0.5px solid var(--brand);
        border-radius: 12px;
        background: rgba(154,0,2,0.03);
        cursor: pointer;
    }
    .payment-radio {
        width: 20px; height: 20px;
        border-radius: 50%;
        border: 2px solid var(--brand);
        background: #fff;
        flex-shrink: 0;
        margin-top: 1px;
        display: flex; align-items: center; justify-content: center;
    }
    .payment-radio-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: var(--brand);
    }
    .payment-label { font-size: 14px; font-weight: 700; color: #111; }
    .payment-desc { font-size: 12px; color: #888; margin-top: 4px; line-height: 1.5; }

    /* ---- Error box ---- */
    .co-error-box {
        display: none;
        margin-top: 14px;
        padding: 12px 16px;
        background: #fef2f2;
        border: 0.5px solid #fca5a5;
        border-radius: 10px;
        font-size: 13px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .co-error-box.hidden { display: none !important; }

    @keyframes shake-button {
        0%, 85% { transform: translateX(0); }
        87%, 91%, 95%, 99% { transform: translateX(-4px); }
        89%, 93%, 97% { transform: translateX(4px); }
        100% { transform: translateX(0); }
    }

    /* ---- Submit Button ---- */
    .co-submit-btn {
        width: 100%;
        padding: 15px;
        background: var(--brand);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.25s;
        margin-top: 8px;
        font-family: inherit;
        animation: shake-button 4s infinite;
    }
    .co-submit-btn:hover:not(:disabled) {
        background: var(--brand-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(154,0,2,0.3);
        animation-play-state: paused;
    }
    .co-submit-btn:disabled { opacity: 0.7; cursor: not-allowed; animation: none; }

    /* ---- Order Summary ---- */
    .order-summary-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #9A0002;
        overflow: hidden;
        position: sticky;
        top: 100px;
    }
    .order-summary-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
        color: white;
    }
    .order-summary-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .order-items-list { 
        padding: 16px 24px; 
        max-height: 200px; 
        overflow-y: auto; 
    }
    .order-items-list::-webkit-scrollbar { width: 6px; }
    .order-items-list::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .order-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 0.5px solid #f0f0f0;
    }
    .order-item:last-child { border-bottom: none; }
    .order-item-img {
        width: 52px; height: 52px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f5f5f5;
        border: 0.5px solid #eee;
    }
    .order-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .order-item-info { flex-grow: 1; min-width: 0; }
    .order-item-name { font-size: 13px; font-weight: 600; color: #222; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .order-item-meta { font-size: 11px; color: #999; margin-top: 3px; }
    .order-item-price { font-size: 14px; font-weight: 700; color: var(--brand); flex-shrink: 0; }

    /* Totals */
    .order-totals { padding: 16px 24px; border-top: 0.5px solid #f0f0f0; }
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        margin-bottom: 10px;
        color: #666;
    }
    .total-row .val { font-weight: 600; color: #333; }
    .total-row.free .val { color: #22c55e; font-weight: 700; }
    .total-grand {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0 0;
        margin-top: 6px;
        border-top: 0.5px solid #e8e8e8;
    }
    .total-grand .lbl { font-size: 15px; font-weight: 800; color: #111; }
    .total-grand .amt { font-size: 18px; font-weight: 800; color: var(--brand); }

    /* COD guarantee badge */
    .cod-badge {
        margin: 0 24px 20px;
        padding: 12px 16px;
        background: rgba(34,197,94,0.06);
        border: 0.5px solid rgba(34,197,94,0.25);
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #444;
    }
    .cod-badge i { color: #22c55e; font-size: 18px; flex-shrink: 0; }

    /* Loading spinner */
    .co-loading {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 32px;
        color: #aaa;
        font-size: 13px;
        gap: 10px;
    }
    .co-spin {
        width: 24px; height: 24px;
        border: 2px solid #e0e0e0;
        border-top-color: var(--brand);
        border-radius: 50%;
        animation: co-spin 0.7s linear infinite;
    }
    @keyframes co-spin { to { transform: rotate(360deg); } }

    /* Empty cart */
    .co-empty { text-align: center; padding: 60px 20px; }
    .co-empty .icon { font-size: 56px; margin-bottom: 16px; }
    .co-empty h3 { font-size: 20px; font-weight: 700; color: #222; margin-bottom: 8px; }
    .co-empty p { font-size: 14px; color: #888; margin-bottom: 24px; }
    .co-shop-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 28px;
        background: var(--brand);
        color: white;
        border-radius: 10px;
        font-size: 14px; font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }
    .co-shop-btn:hover { background: var(--brand-dark); color: white; }

    /* Layout */
    .checkout-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 28px;
        align-items: start;
    }
    @media(max-width: 1024px){
        .checkout-grid { grid-template-columns: 1fr; }
        .order-summary-card { position: static; }
    }
</style>
@endpush

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
                        <a href="{{ route('frontend.shop') }}" class="text-secondary2">Shop</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">Checkout</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-wrap">
    <div class="container">

        {{-- Empty cart guard --}}
        <div id="empty-cart-msg" class="hidden">
            <div class="co-empty">
                <div class="icon">🛒</div>
                <h3>Your cart is empty</h3>
                <p>Add some products before checking out.</p>
                <a href="{{ route('frontend.shop') }}" class="co-shop-btn">
                    <i class="ph ph-shopping-bag"></i> Continue Shopping
                </a>
            </div>
        </div>

        {{-- Main Checkout Grid --}}
        <div id="checkout-content">
            <form id="checkout-form" novalidate class="checkout-grid">

            {{-- ===== LEFT: Form ===== --}}
            <div>
                {{-- Static Checkout Steps --}}
                <div class="checkout-steps-wrapper">
                    <div class="checkout-steps">
                        {{-- Step 1: Done --}}
                        <div class="step-item done">
                            <div class="step-num">
                                <i class="ph ph-check step-icon"></i>
                            </div>
                            <span>Add to cart</span>
                        </div>
                        <div class="step-divider" style="background: #10b981;"></div>

                        {{-- Step 2: Done --}}
                        <div class="step-item done">
                            <div class="step-num">
                                <i class="ph ph-check step-icon"></i>
                            </div>
                            <span>Checkout</span>
                        </div>
                        <div class="step-divider" style="background: #10b981;"></div>

                        {{-- Step 3: Active --}}
                        <div class="step-item active">
                            <div class="step-num">3</div>
                            <span>Delivery Information</span>
                        </div>
                        <div class="step-divider"></div>

                        {{-- Step 4: Pending --}}
                        <div class="step-item">
                            <div class="step-num">4</div>
                            <span>Place Order</span>
                        </div>
                    </div>
                </div>

                    @csrf

                    {{-- Delivery Info Card --}}
                    <div class="co-card">
                        <div class="co-card-header">
                            <div class="co-card-header-icon"><i class="ph ph-map-pin"></i></div>
                            <div>
                                <h3>Delivery Information</h3>
                                <p>Fill in your details to complete the order.</p>
                            </div>
                        </div>
                        <div class="co-card-body">
                            {{-- Full Name --}}
                            <div class="co-field-group">
                                <label class="co-field-label" for="co-name">Full Name <span class="co-field-required">*</span></label>
                                <input class="co-input" id="co-name" name="name" type="text" placeholder="e.g. Mahfuz Rahman" value="{{ $customer->name ?? (auth()->user()->fname ?? '') . ' ' . (auth()->user()->lname ?? '') }}" required />
                            </div>

                            <div class="co-fields-row">
                                {{-- Phone --}}
                                <div class="co-field-group">
                                    <label class="co-field-label" for="co-phone">Phone Number <span class="co-field-required">*</span></label>
                                    <input class="co-input" id="co-phone" name="phone" type="tel" placeholder="e.g. 01XXXXXXXXX" value="{{ $customer->phone ?? auth()->user()->phone ?? '' }}" required />
                                </div>
                                {{-- Email --}}
                                <div class="co-field-group">
                                    <label class="co-field-label" for="co-email">Email <span class="co-field-optional">(optional)</span></label>
                                    <input class="co-input" id="co-email" name="email" type="email" placeholder="you@email.com" value="{{ ($customer && !str_contains($customer->email, '@buzz.local')) ? $customer->email : '' }}" />
                                </div>
                            </div>

                            <div class="co-fields-row">
                                {{-- City --}}
                                <div class="co-field-group">
                                    <label class="co-field-label" for="co-city">City <span class="co-field-required">*</span></label>
                                    <input class="co-input" id="co-city" name="city" type="text" placeholder="e.g. Dhaka" value="{{ $customer->city ?? '' }}" required />
                                </div>
                                {{-- Thana --}}
                                <div class="co-field-group">
                                    <label class="co-field-label" for="co-thana">Thana / Upazila <span class="co-field-required">*</span></label>
                                    <input class="co-input" id="co-thana" name="thana" type="text" placeholder="e.g. Mirpur" value="{{ $customer->thana ?? '' }}" required />
                                </div>
                            </div>

                            {{-- Full Address --}}
                            <div class="co-field-group">
                                <label class="co-field-label" for="co-address">Full Address <span class="co-field-required">*</span></label>
                                <textarea class="co-input" id="co-address" name="address" rows="3" placeholder="House no, Road no, Area..." required>{{ $customer->full_address ?? '' }}</textarea>
                            </div>

                            {{-- Order Notes --}}
                            <div class="co-field-group" style="margin-bottom:0;">
                                <label class="co-field-label" for="co-notes">Order Notes <span class="co-field-optional">(optional)</span></label>
                                <textarea class="co-input" id="co-notes" name="notes" rows="2" placeholder="Any special instructions for your order..."></textarea>
                            </div>
                        </div>
                    </div>

            </div>

            {{-- ===== RIGHT: Order Summary ===== --}}
            <div>
                <div class="order-summary-card">
                    <div class="order-summary-header">
                        <h3><i class="ph ph-shopping-cart-simple"></i> Your Order</h3>
                    </div>

                    {{-- Items (loaded dynamically) --}}
                    <div id="checkout-items" class="order-items-list">
                        <div class="co-loading">
                            <div class="co-spin"></div>
                            Loading cart items...
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="order-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span class="val checkout-subtotal">৳0.00</span>
                        </div>
                        <div class="total-row" id="discount-savings-row" style="display:none;color:#9A0002;">
                            <span>🎉 Discount Savings</span>
                            <span class="val checkout-savings" style="color:#9A0002;font-weight:700;">-৳0.00</span>
                        </div>
                        <div class="total-row free">
                            <span>Shipping</span>
                            <span class="val">Free</span>
                        </div>
                        <div class="total-grand">
                            <span class="lbl">Total</span>
                            <span class="amt checkout-total">৳0.00</span>
                        </div>
                    </div>

                    {{-- COD guarantee --}}
                    <div class="cod-badge">
                        <i class="ph ph-shield-check"></i>
                        <span>Cash on Delivery — Pay when your order arrives.</span>
                    </div>
                </div>
                    {{-- Payment Method Card --}}
                    <div class="co-card" style="margin-top:20px;">
                        <div class="co-card-header">
                            <div class="co-card-header-icon"><i class="ph ph-wallet"></i></div>
                            <div>
                                <h3>Payment Method</h3>
                                <p>Select how you'd like to pay.</p>
                            </div>
                        </div>
                        <div class="co-card-body">
                            <div class="payment-option">
                                <div class="payment-radio">
                                    <div class="payment-radio-dot"></div>
                                </div>
                                <div>
                                    <div class="payment-label">Cash on Delivery (COD)</div>
                                    <div class="payment-desc">Pay with cash when your order is delivered at your doorstep. No advance payment needed.</div>
                                </div>
                            </div>
                            <input type="hidden" name="payment_method" value="cod">
                        </div>
                    </div>

                    {{-- Error message --}}
                    <div id="checkout-error" class="co-error-box hidden" style="margin-bottom:14px;">
                        <i class="ph ph-warning-circle" style="font-size:18px;flex-shrink:0;"></i>
                        <span id="checkout-error-text"></span>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="place-order-btn" class="co-submit-btn">
                        <span id="btn-text"><i class="ph ph-check-circle"></i> &nbsp;Place Order</span>
                        <span id="btn-spinner" class="hidden" style="display:none;align-items:center;gap:8px;">
                            <span class="co-spin"></span> Processing...
                        </span>
                    </button>
            </div>

            </form>
        </div>{{-- end checkout-content --}}
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
    const errorText       = document.getElementById('checkout-error-text');
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

            // Show discount savings row if any
            const savingsRow = document.getElementById('discount-savings-row');
            const savingsEl  = document.querySelector('.checkout-savings');
            if (data.total_savings > 0) {
                savingsEl.textContent    = '-' + fmt(data.total_savings);
                savingsRow.style.display = 'flex';
            } else {
                savingsRow.style.display = 'none';
            }
        })
        .catch(() => {
            itemsContainer.innerHTML = '<div class="co-loading" style="color:#ef4444;"><i class="ph ph-warning" style="font-size:24px;"></i> Could not load cart. Please refresh.</div>';
        });
    }

    function renderItems(items) {
        itemsContainer.innerHTML = items.map(item => {
            const priceHtml = item.has_discount
                ? `<del style="color:#bbb;font-size:12px;">৳${Number(item.original_price).toLocaleString('en-BD', {minimumFractionDigits:2})}</del>
                   <strong style="color:#9A0002;">৳${Number(item.price).toLocaleString('en-BD', {minimumFractionDigits:2})}</strong>
                   <span style="font-size:10px;font-weight:700;color:#fff;background:#9A0002;padding:1px 7px;border-radius:20px;margin-left:2px;">-${Math.round(item.discount_pct)}%</span>`
                : `৳${Number(item.subtotal).toLocaleString('en-BD', {minimumFractionDigits:2})}`;

            return `
            <div class="order-item">
                <a href="/product/${item.slug}" class="order-item-img">
                    <img src="${item.image}" alt="${item.name}" />
                </a>
                <div class="order-item-info">
                    <div class="order-item-name">${item.name}</div>
                    <div class="order-item-meta">
                        ${item.size_name ? `<span style="color:#555">Size: <strong>${item.size_name}</strong></span>` : ''}
                        ${item.size_name && item.color_name ? ' &middot; ' : ''}
                        ${item.color_name ? `<span style="color:#555">Color: <strong>${item.color_name}</strong></span>` : ''}
                        <br>
                        <span style="color:#555">Qty: <strong>${item.quantity}</strong></span>
                    </div>
                </div>
                <div class="order-item-price" style="text-align:right;">${priceHtml}</div>
            </div>`;
        }).join('');
    }

    // ── Handle form submission ─────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const required = ['co-name', 'co-phone', 'co-city', 'co-thana', 'co-address'];
        let valid = true;
        required.forEach(id => {
            const el = document.getElementById(id);
            if (!el.value.trim()) {
                el.classList.add('error');
                valid = false;
            } else {
                el.classList.remove('error');
            }
        });

        if (!valid) {
            showError('Please fill in all required fields.');
            return;
        }

        btnText.style.display = 'none';
        btnSpinner.style.display = 'flex';
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

    // Clear error border on input focus
    form.querySelectorAll('input, textarea').forEach(el => {
        el.addEventListener('focus', () => el.classList.remove('error'));
    });

    function showError(msg) {
        errorText.textContent = msg;
        errorEl.classList.remove('hidden');
        errorEl.style.display = 'flex';
        errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideError() {
        errorEl.classList.add('hidden');
        errorEl.style.display = 'none';
    }

    function resetBtn() {
        btnText.style.display = 'flex';
        btnSpinner.style.display = 'none';
        placeBtn.disabled = false;
    }

    loadCartData();
});
</script>
@endpush
