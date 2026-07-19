@extends('backend.layouts.master')

@section('title', 'System Settings')

@section('content')
<form action="{{ route('settings.backend.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Header Area -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h2 class="fw-bold mb-1 text-dark">System Settings</h2>
            <p class="text-muted mb-0 small">Manage your business configuration and appearance</p>
        </div>
        <button type="submit" class="btn btn-primary shadow-sm rounded-3 px-4 d-flex align-items-center">
            <i class="bi bi-save me-2"></i> Save All Settings
        </button>
    </div>

    <div class="row g-3">
        <!-- Navigation Sidebar -->
        <div class="col-xl-3 col-lg-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px; z-index: 1;">
                <div class="card-body p-2">
                    <div class="nav flex-column nav-pills modern-pills" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button
                            class="nav-link active text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                            id="nav-general-tab" data-bs-toggle="pill" data-bs-target="#nav-general"
                            type="button" role="tab">
                            <i class="bi bi-info-circle me-2"></i> General Info
                        </button>
                        <button
                            class="nav-link text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                            id="nav-branding-tab" data-bs-toggle="pill" data-bs-target="#nav-branding"
                            type="button" role="tab">
                            <i class="bi bi-image me-2"></i> Branding
                        </button>
                        <button
                            class="nav-link text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                            id="nav-pos-tab" data-bs-toggle="pill" data-bs-target="#nav-pos" type="button"
                            role="tab">
                            <i class="bi bi-cart me-2"></i> POS & Currency
                        </button>
                        <button
                            class="nav-link text-start py-2 px-3 rounded-3 mb-0 fw-semibold d-flex align-items-center"
                            id="nav-advanced-tab" data-bs-toggle="pill" data-bs-target="#nav-advanced"
                            type="button" role="tab">
                            <i class="bi bi-layers me-2"></i> Advanced
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-xl-9 col-lg-8">
            <div class="tab-content border-0" id="v-pills-tabContent">

                <!-- General Info Section -->
                <div class="tab-pane fade show active" id="nav-general" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-info-square me-2"></i>Business Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Business Name</label>
                                    <input type="text" class="form-control form-control-sm" name="site_name"
                                        value="{{ $setting->site_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Site Slogan (En)</label>
                                    <input type="text" class="form-control form-control-sm" name="site_title"
                                        value="{{ $setting->site_title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Site Slogan (Bn)</label>
                                    <input type="text" class="form-control form-control-sm" name="site_title_bn"
                                        value="{{ $setting->site_title_bn }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Contact Phone</label>
                                    <input type="text" class="form-control form-control-sm" name="phone"
                                        value="{{ $setting->phone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Email Address</label>
                                    <input type="email" class="form-control form-control-sm" name="email"
                                        value="{{ $setting->email }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Full Address (En)</label>
                                    <textarea class="form-control form-control-sm" name="address" rows="2">{{ $setting->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-dark small">Full Address (Bn)</label>
                                    <textarea class="form-control form-control-sm" name="address_bn" rows="2">{{ $setting->address_bn }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branding Section -->
                <div class="tab-pane fade" id="nav-branding" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                        <h5 class="fw-bold mb-3 text-start text-primary"><i class="bi bi-image me-2"></i>Branding Assets</h5>
                        <div class="row g-4 justify-content-center text-center">
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light">
                                    <h6 class="fw-bold mb-2 small text-dark">Main Logo</h6>
                                    <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm">
                                        <img id="logo-preview"
                                            src="{{ asset('backend/images/logo.png') }}"

                                            class="img-fluid" style="max-height: 80px;">
                                    </div>
                                    <input type="file" class="form-control form-control-sm mt-2" name="logo"
                                        id="logo-input" accept="image/*">
                                    <small class="text-muted d-block mt-1 x-small">Recommended: PNG / 200x50px</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light">
                                    <h6 class="fw-bold mb-2 small text-dark">Favicon</h6>
                                    <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm text-center">
                                        <img id="favicon-preview"
                                            src="{{ asset('backend/images/favicon.png') }}"

                                            class="img-fluid" style="width: 32px; height: 32px;">
                                    </div>
                                    <input type="file" class="form-control form-control-sm mt-2" name="favicon"
                                        id="favicon-input" accept="image/*">
                                    <small class="text-muted d-block mt-1 x-small">Size: 32x32px</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POS & Currency Section -->
                <div class="tab-pane fade" id="nav-pos" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-cart me-2"></i>POS & Currency</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark small">Symbol</label>
                                <input type="text" class="form-control form-control-sm" name="currency_symbol"
                                    value="{{ $setting->currency_symbol }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark small">Name</label>
                                <input type="text" class="form-control form-control-sm" name="currency_name"
                                    value="{{ $setting->currency_name }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark small">ISO Code</label>
                                <input type="text" class="form-control form-control-sm" name="currency_code"
                                    value="{{ $setting->currency_code }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark small">Position</label>
                                <select class="form-select form-select-sm" name="currency_position">
                                    <option value="prefix"
                                        {{ $setting->currency_position == 'prefix' ? 'selected' : '' }}>Prefix ($ 100)</option>
                                    <option value="suffix"
                                        {{ $setting->currency_position == 'suffix' ? 'selected' : '' }}>Suffix (100 $)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small">Invoice Header Style</label>
                                <select class="form-select form-select-sm" name="invoice_view_type">
                                    <option value="both" {{ $setting->invoice_view_type == 'both' ? 'selected' : '' }}>Logo & Text</option>
                                    <option value="logo_only" {{ $setting->invoice_view_type == 'logo_only' ? 'selected' : '' }}>Logo Only</option>
                                    <option value="text_only" {{ $setting->invoice_view_type == 'text_only' ? 'selected' : '' }}>Text Only</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 border-top pt-3 text-dark">Receipt Templates</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small">POS Receipt</label>
                                <select class="form-select form-select-sm p-2" name="pos_receipt_type">
                                    <option value="pos" {{ $setting->pos_receipt_type == 'pos' ? 'selected' : '' }}>Thermal (80mm)</option>
                                    <option value="a4" {{ $setting->pos_receipt_type == 'a4' ? 'selected' : '' }}>Standard (A4)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small">Purchase Orders</label>
                                <select class="form-select form-select-sm p-2" name="purchase_receipt_type">
                                    <option value="a4" {{ $setting->purchase_receipt_type == 'a4' ? 'selected' : '' }}>Standard (A4)</option>
                                    <option value="pos" {{ $setting->purchase_receipt_type == 'pos' ? 'selected' : '' }}>Thermal (80mm)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small">Payment Vouchers</label>
                                <select class="form-select form-select-sm p-2" name="payment_receipt_type">
                                    <option value="pos" {{ $setting->payment_receipt_type == 'pos' ? 'selected' : '' }}>Thermal (80mm)</option>
                                    <option value="a4" {{ $setting->payment_receipt_type == 'a4' ? 'selected' : '' }}>Standard (A4)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Controls Section -->
                <div class="tab-pane fade" id="nav-advanced" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-gear me-2"></i>Advanced Controls</h5>
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <div class="p-3 rounded-4 bg-light border border-light-subtle">
                                    <label class="form-label fw-bold text-dark x-small d-block mb-1">Low Stock Threshold</label>
                                    <div class="input-group input-group-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-warning px-3"><i class="bi bi-exclamation-triangle"></i></span>
                                        <input type="number" class="form-control border-0 bg-white" name="low_stock_limit" value="{{ $setting->low_stock_limit }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 rounded-4 bg-light border border-light-subtle">
                                    <label class="form-label fw-bold text-dark x-small d-block mb-1">Default VAT/Tax (%)</label>
                                    <div class="input-group input-group-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-0 text-primary px-3"><i class="bi bi-percent"></i></span>
                                        <input type="number" step="0.01" class="form-control border-0 bg-white" name="default_vat" value="{{ $setting->default_vat ?? 5 }}">
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 rounded-4 bg-light border border-light-subtle d-flex justify-content-between align-items-center" style="min-height: 86px;">
                                    <div>
                                        <h6 class="fw-bold mb-0 small text-dark">Dark Appearance</h6>
                                        <small class="text-muted x-small">Experimental theme</small>
                                    </div>
                                    <div class="form-check form-switch ps-0">
                                        <input class="form-check-input ms-0" type="checkbox" name="dark_mode"
                                            value="1" {{ $setting->dark_mode ? 'checked' : '' }}
                                            style="width: 40px; height: 20px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
@endsection

@push('css')
    <style>
        .modern-pills .nav-link {
            color: #64748b;
            background: transparent;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .modern-pills .nav-link:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .modern-pills .nav-link.active {
            background: rgba(var(--bs-primary-rgb), 0.05) !important;
            color: var(--bs-primary) !important;
            border-color: rgba(var(--bs-primary-rgb), 0.2);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-control-sm, .form-select-sm {
            border-radius: 8px;
        }

        .rounded-4 {
            border-radius: 0.85rem !important;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .x-small {
            font-size: 0.75rem;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Restore active tab from localStorage
            var activeTab = localStorage.getItem('backend_setting_active_tab');
            if (activeTab) {
                var tabTrigger = new bootstrap.Tab(document.querySelector(activeTab));
                tabTrigger.show();
            }

            // Save active tab to localStorage on change
            var navLinks = document.querySelectorAll('button[data-bs-toggle="pill"]');
            navLinks.forEach(function(link) {
                link.addEventListener('shown.bs.tab', function(event) {
                    var tabId = '#' + event.target.id;
                    localStorage.setItem('backend_setting_active_tab', tabId);
                });
            });

            // Logo Preview
            document.getElementById('logo-input').onchange = function(evt) {
                const [file] = this.files;
                if (file) {
                    document.getElementById('logo-preview').src = URL.createObjectURL(file);
                }
            }
            // Favicon Preview
            document.getElementById('favicon-input').onchange = function(evt) {
                const [file] = this.files;
                if (file) {
                    document.getElementById('favicon-preview').src = URL.createObjectURL(file);
                }
            }
        });
    </script>
@endpush
