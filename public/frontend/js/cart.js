/**
 * BuzzBangladesh Cart Module
 * Uses direct binding (not delegation) for maximum reliability.
 */
(function () {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // ── Toast ──────────────────────────────────────────────────────────────
    window.showToast = function(message, type) {
        type = type || 'success';
        var old = document.getElementById('buzz-toast');
        if (old) old.remove();

        var bg = type === 'error' ? '#dc2626' : '#111';
        var toast = document.createElement('div');
        toast.id = 'buzz-toast';
        toast.style.cssText = [
            'position:fixed', 'bottom:24px', 'right:24px', 'z-index:99999',
            'background:' + bg, 'color:#fff', 'padding:12px 20px',
            'border-radius:12px', 'font-size:14px', 'font-weight:500',
            'box-shadow:0 4px 20px rgba(0,0,0,.25)',
            'display:flex', 'align-items:center', 'gap:8px',
            'transition:opacity .3s, transform .3s',
            'opacity:0', 'transform:translateY(8px)'
        ].join(';');
        toast.innerHTML = '<span>' + (type === 'error' ? '✕' : '✓') + '</span><span>' + message + '</span>';
        document.body.appendChild(toast);

        // Animate in
        setTimeout(function () {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);

        // Auto-dismiss
        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(8px)';
            setTimeout(function () { if (toast.parentNode) toast.remove(); }, 300);
        }, 3000);
    };

    // ── Update cart count badges ───────────────────────────────────────────
    window.updateCartBadges = function(count) {
        document.querySelectorAll('.cart-quantity').forEach(function (el) {
            el.textContent = count;
        });
    };

    // ── Open the side-cart modal ───────────────────────────────────────────
    window.openCartModal = function() {
        var el = document.querySelector('.modal-cart-main');
        if (el) el.classList.add('open');
    };

    // ── POST helper ────────────────────────────────────────────────────────
    function cartPost(url, payload, callback) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(function (r) { return r.json(); })
        .then(function (data) { callback(null, data); })
        .catch(function (err) { callback(err, null); });
    }

    // ── Refresh the side-cart HTML ─────────────────────────────────────────
    window.fetchCart = function() {
        fetch('/cart/render', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var listProduct = document.querySelector('.modal-cart-block .list-product');
            var totalEl     = document.querySelector('.modal-cart-block .total-cart');

            if (listProduct) listProduct.innerHTML = data.html || '';
            if (totalEl)     totalEl.textContent   = '৳' + Number(data.total || 0).toLocaleString('en-BD', { minimumFractionDigits: 2 });

            window.updateCartBadges(data.count || 0);

            // Re-bind remove/update buttons inside the newly rendered HTML
            bindCartItemButtons();
        })
        .catch(function (err) {
            console.error('fetchCart error:', err);
        });
    }

    // ── Bind + / - and Remove inside the side cart ─────────────────────────
    function bindCartItemButtons() {
        // Remove buttons
        document.querySelectorAll('.modal-cart-block .remove-cart-btn').forEach(function (btn) {
            if (btn._cartBound) return;
            btn._cartBound = true;
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var key = btn.getAttribute('data-key');
                cartPost('/cart/remove', { cart_key: key }, function (err, data) {
                    if (!err && data && data.status === 'success') {
                        fetchCart();
                        showToast('Item removed.');
                    }
                });
            });
        });

        // Quantity buttons
        document.querySelectorAll('.modal-cart-block .update-cart-btn').forEach(function (btn) {
            if (btn._cartBound) return;
            btn._cartBound = true;
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var key = btn.getAttribute('data-key');
                var qty = parseInt(btn.getAttribute('data-qty'), 10);
                var endpoint = qty < 1 ? '/cart/remove' : '/cart/update';
                var payload  = qty < 1 ? { cart_key: key } : { cart_key: key, quantity: qty };
                cartPost(endpoint, payload, function (err, data) {
                    if (!err && data && data.status === 'success') {
                        fetchCart();
                        if (qty < 1) showToast('Item removed.');
                    }
                });
            });
        });
    }

    // ── Bind a single Add-to-Cart button ──────────────────────────────────
    function bindAddCartBtn(btn) {
        if (btn._cartBound) return;
        btn._cartBound = true;

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // prevent .product-main or other parent clicks

            var productId = btn.getAttribute('data-id');
            if (!productId) {
                console.warn('Add to cart button missing data-id:', btn);
                return;
            }

            var quantity = 1;
            var colorId  = null;
            var sizeId   = null;

            // On product detail page, read selected color/size/qty
            var detailPage = document.querySelector('.product-detail');
            if (detailPage && detailPage.contains(btn)) {
                var qtyEl = detailPage.querySelector('.choose-quantity .quantity');
                if (qtyEl) quantity = parseInt(qtyEl.textContent, 10) || 1;

                var colorItems = detailPage.querySelectorAll('.color-item');
                if (colorItems.length > 0) {
                    var activeColor = detailPage.querySelector('.color-item.active');
                    if (activeColor) {
                        colorId = activeColor.getAttribute('data-color-id');
                    } else {
                        if (window.showToast) window.showToast('Please select a color first.', 'error');
                        else alert('Please select a color first.');
                        return;
                    }
                }

                var sizeItems = detailPage.querySelectorAll('.size-item');
                if (sizeItems.length > 0) {
                    var activeSize = detailPage.querySelector('.size-item.active');
                    if (activeSize) {
                        sizeId = activeSize.getAttribute('data-size-id');
                    } else {
                        if (window.showToast) window.showToast('Please select a size first.', 'error');
                        else alert('Please select a size first.');
                        return;
                    }
                }
            }

            // Disable button during request
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.6';

            cartPost('/cart/add', {
                product_id: productId,
                quantity:   quantity,
                color_id:   colorId,
                size_id:    sizeId
            }, function (err, data) {
                btn.style.pointerEvents = '';
                btn.style.opacity = '';

                if (err) {
                    console.error('Add to cart network error:', err);
                    window.showToast('Network error. Try again.', 'error');
                    return;
                }

                if (data && data.status === 'success') {
                    window.updateCartBadges(data.cart_count);
                    window.fetchCart();       // refresh side-cart list
                    window.openCartModal();   // open the panel
                    window.showToast('Added to cart!');
                } else {
                    window.showToast((data && data.message) || 'Could not add to cart.', 'error');
                }
            });
        });
    }

    // ── Bind all .add-cart-btn on the page ────────────────────────────────
    function bindAllAddCartBtns() {
        document.querySelectorAll('.add-cart-btn').forEach(bindAddCartBtn);
    }

    // ── Open cart modal trigger (.open-cart-modal) ─────────────────────────
    function bindCartModalTriggers() {
        document.querySelectorAll('.open-cart-modal').forEach(function (el) {
            if (el._cartModalBound) return;
            el._cartModalBound = true;
            el.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                window.openCartModal();
            });
        });
    }

    // ── MutationObserver: catch dynamically injected add-cart buttons ──────
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
            m.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;
                if (node.classList && node.classList.contains('add-cart-btn')) {
                    bindAddCartBtn(node);
                }
                node.querySelectorAll && node.querySelectorAll('.add-cart-btn').forEach(bindAddCartBtn);
            });
        });
    });

    // ── Init ──────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        bindAllAddCartBtns();
        bindCartModalTriggers();
        observer.observe(document.body, { childList: true, subtree: true });
        fetchCart(); // load initial cart state
    });

    // If DOMContentLoaded already fired (scripts at bottom of body)
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        bindAllAddCartBtns();
        bindCartModalTriggers();
        observer.observe(document.body, { childList: true, subtree: true });
        fetchCart();
    }

})();
