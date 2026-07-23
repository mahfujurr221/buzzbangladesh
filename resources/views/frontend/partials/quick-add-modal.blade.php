{{-- ======================================================
     QUICK ADD TO CART MODAL
     Triggered when clicking "Add To Cart" from product grids.
     ====================================================== --}}

<div id="quick-add-modal" style="
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    align-items: flex-end;
    justify-content: center;
">
    {{-- Backdrop --}}
    <div id="quick-add-backdrop" style="
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(2px);
        cursor: pointer;
    "></div>

    {{-- Panel --}}
    <div id="quick-add-panel" style="
        position: relative;
        background: #fff;
        border-radius: 20px 20px 0 0;
        width: 100%;
        max-width: 520px;
        padding: 28px 24px 36px;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
        transform: translateY(100%);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 1;
    ">
        {{-- Drag handle --}}
        <div style="width:48px;height:4px;background:#e0e0e0;border-radius:4px;margin:0 auto 20px;"></div>

        {{-- Product Info --}}
        <div style="display:flex;gap:16px;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #f0f0f0;">
            <img id="qam-image" src="" alt="" style="width:72px;height:72px;object-fit:cover;border-radius:12px;flex-shrink:0;" />
            <div style="flex:1;min-width:0;">
                <div id="qam-name" style="font-weight:700;font-size:15px;line-height:1.3;"></div>
                <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                    <span id="qam-price" style="font-size:16px;font-weight:700;color:#9A0002;"></span>
                    <span id="qam-original-price" style="font-size:13px;color:#999;text-decoration:line-through;display:none;"></span>
                    <span id="qam-discount-badge" style="font-size:11px;font-weight:700;color:#fff;background:#9A0002;padding:2px 8px;border-radius:20px;display:none;"></span>
                </div>
            </div>
        </div>

        {{-- Color Selection --}}
        <div id="qam-color-section" style="margin-bottom:16px;display:none;">
            <div style="font-size:13px;font-weight:600;color:#333;margin-bottom:10px;">
                Color: <span id="qam-selected-color" style="color:#9A0002;font-weight:700;"></span>
            </div>
            <div id="qam-colors" style="display:flex;gap:10px;flex-wrap:wrap;"></div>
        </div>

        {{-- Size Selection --}}
        <div id="qam-size-section" style="margin-bottom:16px;display:none;">
            <div style="font-size:13px;font-weight:600;color:#333;margin-bottom:10px;">
                Size: <span id="qam-selected-size" style="color:#9A0002;font-weight:700;"></span>
            </div>
            <div id="qam-sizes" style="display:flex;gap:8px;flex-wrap:wrap;"></div>
        </div>

        {{-- Stock Warning --}}
        <div id="qam-stock-warning" style="
            display:none;
            padding: 10px 14px;
            background: #fff3f3;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 14px;
        ">⚠️ This combination is out of stock.</div>

        {{-- Quantity --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <div style="font-size:13px;font-weight:600;color:#333;">Quantity:</div>
            <div style="display:flex;align-items:center;border:1px solid #e0e0e0;border-radius:999px;overflow:hidden;">
                <button type="button" id="qam-qty-minus" style="width:36px;height:36px;font-size:18px;border:none;background:none;cursor:pointer;color:#9A0002;font-weight:700;display:flex;align-items:center;justify-content:center;">−</button>
                <span id="qam-qty" style="min-width:32px;text-align:center;font-weight:700;font-size:15px;">1</span>
                <button type="button" id="qam-qty-plus" style="width:36px;height:36px;font-size:18px;border:none;background:none;cursor:pointer;color:#9A0002;font-weight:700;display:flex;align-items:center;justify-content:center;">+</button>
            </div>
        </div>

        {{-- Add to Cart Button --}}
        <button type="button" id="qam-add-btn" style="
            width:100%;
            padding: 14px;
            border-radius: 999px;
            border: none;
            background: #9A0002;
            color: white;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            transition: opacity 0.2s;
        ">Add to Cart</button>

        <a id="qam-view-detail" href="#" style="
            display:block;
            text-align:center;
            margin-top:14px;
            font-size:13px;
            color:#666;
            text-decoration:underline;
        ">View full details</a>
    </div>
</div>

<style>
    /* Color items (Button style) */
    .qam-color-dot {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 999px; /* Pill shape */
        border: 1px solid #e5e7eb;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        color: #333;
        user-select: none;
    }
    .qam-color-dot:hover:not(.out-of-stock) {
        border-color: #9A0002;
    }
    .qam-color-dot.active {
        border-color: #9A0002;
        background: #9A0002;
        color: #fff;
        font-weight: 600;
    }
    .qam-color-dot.active .color-dot-indicator {
        border-color: rgba(255,255,255,0.5) !important;
    }
    .qam-color-dot.out-of-stock {
        opacity: 0.4 !important;
        cursor: not-allowed !important;
        position: relative;
    }
    .qam-color-dot.out-of-stock::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 5%;
        width: 90%;
        height: 1.5px;
        background-color: #9A0002;
        transform: rotate(-15deg);
        z-index: 10;
    }
    .qam-color-dot .tag-action {
        display: none; /* Replaced by inline text */
    }

    /* Size items (Button style) */
    .qam-size-btn {
        padding: 10px 24px;
        border-radius: 999px; /* Pill shape */
        display: inline-flex; 
        align-items: center; 
        justify-content: center;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #333;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 48px;
        user-select: none;
    }
    .qam-size-btn:hover:not(.out-of-stock) {
        border-color: #9A0002;
    }
    .qam-size-btn.active {
        background: #9A0002 !important;
        color: #fff !important;
        border-color: #9A0002 !important;
        font-weight: 600;
    }
    .qam-size-btn.out-of-stock {
        opacity: 0.4 !important;
        pointer-events: none !important;
        position: relative;
        overflow: hidden;
    }
    .qam-size-btn.out-of-stock::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 5%;
        width: 90%;
        height: 1.5px;
        background-color: #9A0002;
        transform: rotate(-15deg);
        z-index: 10;
    }
</style>

@push('scripts')
<script>
(function () {
    const modal    = document.getElementById('quick-add-modal');
    const panel    = document.getElementById('quick-add-panel');
    const backdrop = document.getElementById('quick-add-backdrop');

    let state = {
        productId: null,
        slug: null,
        variations: [],
        selectedColorId: null,
        selectedSizeId: null,
        quantity: 1,
    };

    function openModal(btn) {
        state.productId      = btn.dataset.id;
        state.slug           = btn.dataset.slug;
        state.variations     = JSON.parse(btn.dataset.variations || '[]');
        state.selectedColorId = null;
        state.selectedSizeId  = null;
        state.quantity        = 1;

        // Fill product info
        document.getElementById('qam-image').src        = btn.dataset.image;
        document.getElementById('qam-name').textContent = btn.dataset.name;
        document.getElementById('qam-view-detail').href = '/product/' + state.slug;
        document.getElementById('qam-qty').textContent  = '1';

        const price    = parseFloat(btn.dataset.price);
        const origPrice = parseFloat(btn.dataset.originalPrice);
        const hasDiscount = btn.dataset.hasDiscount === '1';
        const discPct = btn.dataset.discountPct;

        document.getElementById('qam-price').textContent = '৳' + price.toLocaleString('en-BD', {minimumFractionDigits: 2});
        const origEl = document.getElementById('qam-original-price');
        const badgeEl = document.getElementById('qam-discount-badge');
        if (hasDiscount) {
            origEl.textContent = '৳' + origPrice.toLocaleString('en-BD', {minimumFractionDigits: 2});
            origEl.style.display = 'inline';
            badgeEl.textContent  = '-' + discPct + '%';
            badgeEl.style.display = 'inline';
        } else {
            origEl.style.display  = 'none';
            badgeEl.style.display = 'none';
        }

        renderColors();
        renderSizes();
        checkStock();

        // Show modal
        modal.style.display = 'flex';
        requestAnimationFrame(() => {
            panel.style.transform = 'translateY(0)';
        });
    }

    function closeModal() {
        panel.style.transform = 'translateY(100%)';
        setTimeout(() => { modal.style.display = 'none'; }, 350);
    }

    function getAvailableColors() {
        const colorMap = {};
        state.variations.forEach(v => {
            if (v.color_id && !colorMap[v.color_id]) {
                colorMap[v.color_id] = { id: v.color_id, name: v.color, code: v.color_code };
            }
        });
        return Object.values(colorMap);
    }

    function getAvailableSizes() {
        const sizeMap = {};
        state.variations
            .filter(v => !state.selectedColorId || v.color_id == state.selectedColorId)
            .forEach(v => {
                if (v.size_id && !sizeMap[v.size_id]) {
                    sizeMap[v.size_id] = { id: v.size_id, name: v.size };
                }
            });
        return Object.values(sizeMap);
    }

    function isOutOfStock(colorId, sizeId) {
        return !state.variations.some(v =>
            (!colorId || v.color_id == colorId) &&
            (!sizeId  || v.size_id  == sizeId) &&
            v.stock > 0
        );
    }

    function renderColors() {
        const colors = getAvailableColors();
        const section = document.getElementById('qam-color-section');
        const container = document.getElementById('qam-colors');
        if (!colors.length) { section.style.display = 'none'; return; }
        
        section.style.display = 'block';

        // Auto-select first available color if none selected
        if (!state.selectedColorId) {
            const firstAvailable = colors.find(c => !isOutOfStock(c.id, null));
            if (firstAvailable) {
                state.selectedColorId = firstAvailable.id;
            }
        }

        container.innerHTML = colors.map(c => {
            const oos = isOutOfStock(c.id, null);
            const isSelected = state.selectedColorId == c.id;
            return `<div class="qam-color-dot ${isSelected ? 'active' : ''} ${oos ? 'out-of-stock' : ''}"
                        data-color-id="${c.id}"
                        data-color-name="${c.name}">
                        <div class="color-dot-indicator" style="width:14px;height:14px;border-radius:50%;flex-shrink:0;background-color:${c.code || c.name || '#ddd'};border:1px solid #ddd;"></div>
                        <span class="color-item-name" style="text-transform:capitalize;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${c.name}</span>
                    </div>`;
        }).join('');

        // Update selected text
        const selectedObj = colors.find(c => c.id == state.selectedColorId);
        document.getElementById('qam-selected-color').textContent = selectedObj ? selectedObj.name : '';

        container.querySelectorAll('.qam-color-dot').forEach(dot => {
            dot.addEventListener('click', () => {
                if (dot.classList.contains('out-of-stock')) return;
                state.selectedColorId = dot.dataset.colorId;
                // state.selectedSizeId  = null; // removed to preserve selection if available
                renderColors();
                renderSizes();
                checkStock();
            });
        });
    }

    function renderSizes() {
        const sizes = getAvailableSizes();
        const section = document.getElementById('qam-size-section');
        const container = document.getElementById('qam-sizes');
        if (!sizes.length) { section.style.display = 'none'; return; }
        
        section.style.display = 'block';

        container.innerHTML = sizes.map(s => {
            const oos = isOutOfStock(state.selectedColorId, s.id);
            const isSelected = state.selectedSizeId == s.id;
            return `<div class="qam-size-btn ${isSelected ? 'active' : ''} ${oos ? 'out-of-stock' : ''}"
                            data-size-id="${s.id}" data-size-name="${s.name}" title="${s.name}">
                        ${s.name}
                    </div>`;
        }).join('');

        container.querySelectorAll('.qam-size-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('out-of-stock')) return;
                state.selectedSizeId = btn.dataset.sizeId;
                document.getElementById('qam-selected-size').textContent = btn.dataset.sizeName;
                renderSizes();
                checkStock();
            });
        });
    }

    function checkStock() {
        const warning = document.getElementById('qam-stock-warning');
        const addBtn  = document.getElementById('qam-add-btn');
        if (state.selectedColorId && state.selectedSizeId) {
            const oos = isOutOfStock(state.selectedColorId, state.selectedSizeId);
            warning.style.display = oos ? 'block' : 'none';
            addBtn.disabled = oos;
            addBtn.style.opacity = oos ? '0.5' : '1';
        } else {
            warning.style.display = 'none';
            addBtn.disabled = false;
            addBtn.style.opacity = '1';
        }
    }

    // Qty buttons
    document.getElementById('qam-qty-minus').addEventListener('click', () => {
        if (state.quantity > 1) {
            state.quantity--;
            document.getElementById('qam-qty').textContent = state.quantity;
        }
    });
    document.getElementById('qam-qty-plus').addEventListener('click', () => {
        state.quantity++;
        document.getElementById('qam-qty').textContent = state.quantity;
    });

    // Add to cart
    document.getElementById('qam-add-btn').addEventListener('click', () => {
        const colors = getAvailableColors();
        const sizes  = getAvailableSizes();

        if (colors.length && !state.selectedColorId) {
            if (window.showToast) window.showToast('Please select a color first.', 'error');
            else alert('Please select a color first.');
            return;
        }
        if (sizes.length && !state.selectedSizeId) {
            if (window.showToast) window.showToast('Please select a size first.', 'error');
            else alert('Please select a size first.');
            return;
        }

        const btn = document.getElementById('qam-add-btn');
        btn.textContent = 'Adding...';
        btn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                product_id: state.productId,
                quantity:   state.quantity,
                color_id:   state.selectedColorId || null,
                size_id:    state.selectedSizeId  || null,
            }),
        })
        .then(r => r.json())
        .then(data => {
            btn.textContent = 'Add to Cart';
            btn.disabled = false;
            if (data.status === 'success') {
                closeModal();
                if (window.updateCartBadges) window.updateCartBadges(data.cart_count);
                if (window.fetchCart) window.fetchCart();
                if (window.openCartModal) window.openCartModal();
            } else {
                if (window.showToast) window.showToast(data.message || 'Could not add to cart.', 'error');
                else alert(data.message || 'Could not add to cart.');
            }
        })
        .catch(() => {
            btn.textContent = 'Add to Cart';
            btn.disabled = false;
            if (window.showToast) window.showToast('Network error. Please try again.', 'error');
            else alert('Network error. Please try again.');
        });
    });

    // Close
    backdrop.addEventListener('click', closeModal);

    // Intercept all .quick-add-trigger clicks
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('.quick-add-trigger');
        if (trigger) {
            e.preventDefault();
            e.stopPropagation();
            openModal(trigger);
        }
    });
})();
</script>
@endpush
