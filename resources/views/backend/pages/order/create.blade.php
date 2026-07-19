@extends('backend.layouts.master')

@section('title', 'POS Order (Manual)')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f4f6f9; overflow: hidden !important; }
        .pos-container { height: calc(100vh - 140px); display: flex; flex-direction: column; }
        .product-grid-container { padding: 10px; }
        .product-card {
            background: #fff; border-radius: 8px; border: 1px solid #e0e0e0;
            overflow: hidden; cursor: pointer; transition: 0.2s all; position: relative;
        }
        .product-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-color: #0d6efd; }
        .product-card .img-container { height: 90px; background: #f8f9fa; padding: 5px; text-align: center; }
        .product-card .img-container img { max-height: 100%; max-width: 100%; object-fit: contain; }
        .product-card .badge-stock { position: absolute; top: 5px; right: 5px; font-size: 0.65rem; padding: 3px 6px; border-radius: 4px; }
        .product-card .card-body { padding: 8px; text-align: center; }
        .product-card .product-title { font-size: 0.75rem; font-weight: 600; margin-bottom: 2px; height: 32px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .product-card .product-price { font-size: 0.85rem; font-weight: 700; color: #0d6efd; }
        .cart-container { background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; height: 100%; overflow: hidden; }
        .cart-table-wrapper { flex-grow: 1; overflow-y: auto; min-height: 150px; }
        .cart-checkout-dock { background: #f8f9fa; border-top: 1px solid #e9ecef; padding: 15px; border-radius: 0 0 8px 8px; }
        .qty-input { width: 60px; text-align: center; border: 1px solid #ced4da; border-radius: 4px; padding: 2px; }
        .select2-container .select2-selection--single { height: 38px; border-color: #d9dee3; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }

        @media (max-width: 768px) {
            body { overflow: auto; }
            .pos-container { height: auto; display: block; overflow: visible; }
            .col-md-7.h-100, .col-md-5.h-100 { height: auto !important; margin-bottom: 20px; }
            .card.overflow-auto { max-height: 60vh; }
            .cart-container { max-height: none; }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-3 pos-container">
    <div class="row h-100 g-3">
        <!-- LEFT PANEL (col-md-7) -->
        <div class="col-md-7 d-flex flex-column h-100">
            <div class="card border-0 shadow-sm mb-3 flex-shrink-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bx bx-input-cursor-text me-2"></i>Sales Input</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.online') }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 fw-bold">
                            <i class="bx bx-receipt me-1"></i> Sale List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Product Search -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">Product Search</label>
                            <input type="text" id="product_search" class="form-control form-control-lg bg-light border-light" placeholder="Scan barcode or type to search..." autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="card border-0 shadow-sm flex-grow-1 d-flex flex-column overflow-hidden">
                <div class="card-body p-0 product-grid-container flex-grow-1 overflow-auto" id="product_grid">
                    <div class="w-100 text-center py-5 text-muted">
                        <i class="bx bx-loader-alt bx-spin fs-2 mb-2"></i>
                        <p>Loading products...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL (col-md-5) -->
        <div class="col-md-5 h-100">
            <div class="cart-container">
                <form id="orderForm" class="h-100 d-flex flex-column overflow-hidden" onkeydown="return event.key != 'Enter';">
                    
                    <!-- Hidden fields for new customer -->
                    <input type="hidden" name="customer_name" id="hidden_c_name">
                    <input type="hidden" name="customer_phone" id="hidden_c_phone">
                    <input type="hidden" name="city" id="hidden_c_city">
                    <input type="hidden" name="thana" id="hidden_c_thana">
                    <input type="hidden" name="shipping_address" id="hidden_c_address">

                    <div class="p-3 border-bottom bg-white flex-shrink-0" style="border-radius: 8px 8px 0 0;">
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1">Invoice No</label>
                                <div class="bg-light border border-light rounded px-2 py-1 fw-bold text-primary small">
                                    # {{ 'ORD-' . strtoupper(substr(uniqid(), -6)) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1">Date</label>
                                <input type="date" name="sale_date" class="form-control form-control-sm bg-light border-light" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        
                        <div class="mb-0">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-1">Customer</label>
                            <div class="d-flex gap-1">
                                <div class="flex-grow-1">
                                    <select name="customer_id" id="customer_select" class="form-control select2">
                                        <option value="">Walk-in Customer</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                    <i class="bx bx-user-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light flex-shrink-0">
                        <h6 class="mb-0 fw-bold small text-muted"><i class="bx bx-cart me-1"></i> Invoice Items</h6>
                        <button type="button" class="btn btn-sm btn-link text-danger py-0 fw-bold text-decoration-none" onclick="clearCart()">
                            <i class="bx bx-trash"></i> Clear
                        </button>
                    </div>

                    <div class="cart-table-wrapper bg-white">
                        <table class="table table-hover mb-0 cart-table">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th width="45%" class="ps-3 border-bottom-0 text-muted small text-uppercase">Item</th>
                                    <th width="15%" class="text-center border-bottom-0 text-muted small text-uppercase">Qty</th>
                                    <th width="15%" class="text-center border-bottom-0 text-muted small text-uppercase">Price</th>
                                    <th width="20%" class="text-end border-bottom-0 text-muted small text-uppercase">Total</th>
                                    <th width="5%" class="text-center border-bottom-0"></th>
                                </tr>
                            </thead>
                            <tbody id="cart_body">
                                <tr><td colspan="5" class="text-center text-muted py-5">No items in cart</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-checkout-dock bg-light border-top p-3 flex-shrink-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Sub Total</span>
                            <span class="fw-bold small"><span id="cart_subtotal">0.00</span> ৳</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-1 align-items-center">
                            <span class="text-muted small">Discount -</span>
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <input type="number" class="form-control text-end border-white shadow-sm" id="discount_amount" name="discount" value="0" oninput="calculateTotals()">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-1 align-items-center">
                            <span class="text-muted small">Delivery +</span>
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <input type="number" class="form-control text-end border-white shadow-sm" id="shipping_cost" name="shipping_cost" value="0" oninput="calculateTotals()">
                            </div>
                        </div>

                        <div class="border-top border-bottom py-1 my-1 d-flex justify-content-between align-items-center" style="background: #e0e7ff50; margin: 0 -1rem; padding: 0.25rem 1rem;">
                            <span class="fw-bold text-primary small">Invoice Total</span>
                            <span class="fs-5 fw-bold text-primary"><span id="cart_total">0.00</span> ৳</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <span class="fw-bold small">Paid Amount</span>
                            <input type="number" name="paid_amount" id="paid_amount" class="form-control form-control-sm text-end fw-bold border-success text-success py-0 shadow-sm" placeholder="0" style="width: 120px; height: 30px;">
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="button" class="btn btn-success btn-sm fw-bold py-1" onclick="fillFullPaid()">FULL PAID</button>
                            <div class="row g-2">
                                <div class="col-5">
                                    <button type="button" class="btn btn-warning w-100 py-2 rounded-3 fw-bold shadow-sm text-dark border-0">
                                        <i class="bx bx-save me-1"></i> SAVE
                                    </button>
                                </div>
                                <div class="col-7">
                                    <button type="submit" id="btnPlaceOrder" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-lg" disabled>
                                        <i class="bx bx-printer me-1"></i> INVOICE
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title fw-bold"><i class="bx bx-user-plus me-2"></i>Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Customer Name *</label>
                        <input type="text" id="new_customer_name" class="form-control border-white shadow-sm" placeholder="Enter name">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Phone *</label>
                        <input type="text" id="new_customer_phone" class="form-control border-white shadow-sm" placeholder="Enter phone">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">City</label>
                        <input type="text" id="new_customer_city" class="form-control border-white shadow-sm" placeholder="City">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">Thana</label>
                        <input type="text" id="new_customer_thana" class="form-control border-white shadow-sm" placeholder="Thana">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Full Address</label>
                        <textarea id="new_customer_address" class="form-control border-white shadow-sm" rows="2" placeholder="Enter address"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-light fw-bold px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary fw-bold px-4 rounded-pill" onclick="saveNewCustomer()">
                    <i class="bx bx-save me-2"></i>Save Customer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let cart = [];
    let products = [];
    
    // Load Products
    function loadProducts() {
        let q = $('#product_search').val();
        
        $.ajax({
            url: "{{ route('orders.searchProducts') }}",
            data: { q: q },
            success: function(res) {
                products = res;
                renderProductGrid();
            }
        });
    }

    function renderProductGrid() {
        let html = '';
        if (products.length === 0) {
            html = '<div class="w-100 text-center py-5 text-muted">No products found.</div>';
        } else {
            html = '<div class="row g-2">';
            products.forEach(item => {
                let stockClass = item.stock > 0 ? 'bg-success' : 'bg-danger';
                let stockText = item.stock > 0 ? `Stock: ${item.stock}` : 'Out of Stock';
                let defaultImg = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22150%22%20height%3D%22150%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20width%3D%22150%22%20height%3D%22150%22%20fill%3D%22%23eeeeee%22%2F%3E%3Ctext%20x%3D%2275%22%20y%3D%2275%22%20font-family%3D%22sans-serif%22%20font-size%3D%2214%22%20fill%3D%22%23aaaaaa%22%20text-anchor%3D%22middle%22%20alignment-baseline%3D%22middle%22%3ENo%20Image%3C%2Ftext%3E%3C%2Fsvg%3E';
                let imgSrc = item.image ? item.image : defaultImg;
                
                html += `
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card h-100" onclick="addToCart(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, ${item.stock})">
                            <span class="badge ${stockClass} badge-stock text-white">${stockText}</span>
                            <div class="img-container">
                                <img src="${imgSrc}" alt="${item.name}" onerror="this.onerror=null; this.src='${defaultImg}'">
                            </div>
                            <div class="card-body">
                                <div class="product-title" title="${item.name}">${item.name}</div>
                                <div class="product-price">৳${parseFloat(item.price).toFixed(2)}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        }
        $('#product_grid').html(html);
    }

    // Add to Cart
    function addToCart(id, name, price, maxStock) {
        if (maxStock <= 0) {
            toast('This product is out of stock.', 'error');
            return;
        }

        let existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty < maxStock) {
                existing.qty++;
            } else {
                showToast('Cannot add more than available stock.', 'warning');
            }
        } else {
            cart.push({
                id: id,
                name: name,
                price: parseFloat(price),
                qty: 1,
                maxStock: maxStock
            });
        }
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }
    
    function updateQty(index, input) {
        let val = parseInt(input.value);
        let max = cart[index].maxStock;
        
        if (isNaN(val) || val < 1) val = 1;
        if (val > max) val = max;
        
        cart[index].qty = val;
        input.value = val;
        renderCart();
    }

    // Render Cart
    function renderCart() {
        let html = '';
        let subtotal = 0;
        
        if (cart.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted py-5">No items in cart</td></tr>';
            $('#btnPlaceOrder').prop('disabled', true);
        } else {
            cart.forEach((item, index) => {
                let lineTotal = item.price * item.qty;
                subtotal += lineTotal;
                
                html += `
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold" style="font-size: 0.8rem; line-height: 1.2;">${item.name}</div>
                        </td>
                        <td class="text-center">
                            <input type="number" class="qty-input bg-light" value="${item.qty}" min="1" max="${item.maxStock}" onchange="updateQty(${index}, this)">
                        </td>
                        <td class="text-center text-muted small">৳${item.price.toFixed(2)}</td>
                        <td class="text-end fw-bold text-primary">৳${lineTotal.toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm text-danger p-0" onclick="removeFromCart(${index})"><i class="bx bx-x fs-4"></i></button>
                        </td>
                    </tr>
                `;
            });
            $('#btnPlaceOrder').prop('disabled', false);
        }
        
        $('#cart_body').html(html);
        $('#cart_subtotal').text(subtotal.toFixed(2));
        $('#cart_subtotal').data('val', subtotal);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = parseFloat($('#cart_subtotal').data('val')) || 0;
        let discount = parseFloat($('#discount_amount').val()) || 0;
        let shipping = parseFloat($('#shipping_cost').val()) || 0;
        
        let grandTotal = (subtotal - discount) + shipping;
        if (grandTotal < 0) grandTotal = 0;
        
        $('#cart_total').text(grandTotal.toFixed(2));
    }
    
    function fillFullPaid() {
        let grandTotal = parseFloat($('#cart_total').text()) || 0;
        $('#paid_amount').val(grandTotal);
    }

    function saveNewCustomer() {
        let name = $('#new_customer_name').val();
        let phone = $('#new_customer_phone').val();
        
        if (!name || !phone) {
            showToast('Name and Phone are required!', 'error');
            return;
        }
        
        $('#hidden_c_name').val(name);
        $('#hidden_c_phone').val(phone);
        $('#hidden_c_city').val($('#new_customer_city').val());
        $('#hidden_c_thana').val($('#new_customer_thana').val());
        $('#hidden_c_address').val($('#new_customer_address').val());
        
        // Add to select2 visually
        let newOption = new Option(name + ' (' + phone + ')', 'NEW', true, true);
        $('#customer_select').append(newOption).trigger('change');
        
        $('#addCustomerModal').modal('hide');
        showToast('Customer added to current order!', 'success');
    }

    function showToast(msg, type = 'success') {
        if (typeof toastr !== 'undefined') {
            toastr[type === 'error' ? 'error' : type](msg);
        } else {
            alert(msg);
        }
    }

    $(document).ready(function() {
        loadProducts();
        
        // Live search
        $('#product_search').on('keyup', function() {
            let q = $(this).val().toLowerCase();
            if(q.length === 0) {
                renderProductGrid();
            } else {
                let filtered = products.filter(item => 
                    item.name.toLowerCase().includes(q) || 
                    (item.sku && item.sku.toLowerCase().includes(q))
                );
                
                let tmp = products;
                products = filtered;
                renderProductGrid();
                products = tmp; // restore
            }
        });

        $('#product_search').on('keypress', function(e) {
            if (e.which == 13) {
                loadProducts();
                return false;
            }
        });
        
        // Select2 for Customer
        $('#customer_select').select2({
            placeholder: 'Search name or type phone...',
            ajax: {
                url: "{{ route('orders.searchCustomers') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });
        
        // Form Submit
        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            
            if (cart.length === 0) return;
            
            let btn = $('#btnPlaceOrder');
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Processing...');
            
            let formData = $(this).serializeArray();
            let data = {};
            formData.forEach(item => {
                data[item.name] = item.value;
            });
            
            if (data.customer_id === 'NEW') {
                data.customer_id = ''; // Let backend use customer_name and phone
            }
            
            data.cart = cart;
            
            $.ajax({
                url: "{{ route('orders.store') }}",
                type: "POST",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bx bx-printer me-1"></i> INVOICE');
                    let res = xhr.responseJSON;
                    if (res && res.errors) {
                        let msg = Object.values(res.errors)[0][0];
                        showToast(msg, 'error');
                    } else {
                        showToast(res?.message || 'Failed to process order', 'error');
                    }
                }
            });
        });
    });
</script>
@endpush
