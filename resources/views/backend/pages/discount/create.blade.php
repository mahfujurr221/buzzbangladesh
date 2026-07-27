@extends('backend.layouts.master')

@section('title', 'Add Discount Rule')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .level-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 4px;
    }
    .level-option {
        border: 2px solid #e4e6eb;
        border-radius: 12px;
        padding: 16px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #fff;
    }
    .level-option:hover {
        border-color: #696cff;
        background: #f4f5fa;
    }
    .level-option.selected {
        border-color: #696cff;
        background: linear-gradient(135deg, #f4f5fa, #e9eaff);
        box-shadow: 0 4px 15px rgba(105, 108, 255, 0.15);
    }
    .level-option i {
        font-size: 28px;
        margin-bottom: 8px;
        display: block;
    }
    .level-option .level-title {
        font-weight: 700;
        font-size: 14px;
        color: #566a7f;
    }
    .level-option .level-desc {
        font-size: 11px;
        color: #a1acb8;
        margin-top: 4px;
    }
    .level-option.selected .level-title,
    .level-option.selected .level-desc {
        color: #696cff;
    }
    .level-option.selected i {
        color: #696cff;
    }
    .discount-preview-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: white;
        padding: 24px;
        text-align: center;
    }
    .discount-preview-box .pct {
        font-size: 52px;
        font-weight: 900;
        line-height: 1;
    }
    .discount-preview-box .label {
        font-size: 14px;
        opacity: 0.85;
        margin-top: 6px;
    }
    .cascade-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 13px;
        color: #0369a1;
    }
    .cascade-info i {
        font-size: 16px;
        vertical-align: middle;
    }

    /* Fix: Variations (SKUs) select2 field too small */
    #variation_ids + .select2-container .select2-selection--multiple {
        min-height: 130px;
        padding: 6px;
    }
    #variation_ids + .select2-container .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 0;
    }
</style>
@endpush

@section('content')
<form action="{{ route('discounts.store') }}" method="POST" id="discountForm">
    @csrf

    <div class="row">
        {{-- ── Left Column ── --}}
        <div class="col-12 col-lg-8">

            {{-- Discount Level --}}
            <x-modern.card title="Discount Level" class="mb-4" icon="bx bx-layer">
                <p class="text-muted mb-3">Choose how this discount will be applied in the hierarchy.</p>

                <div class="level-selector">
                    {{-- Category --}}
                    <div class="level-option" data-level="category" onclick="selectLevel('category')">
                        <i class="bx bx-grid-alt text-purple" style="color:#7c3aed;"></i>
                        <div class="level-title">Category</div>
                        <div class="level-desc">All products + all variations in a category</div>
                    </div>
                    {{-- Product --}}
                    <div class="level-option selected" data-level="product" onclick="selectLevel('product')">
                        <i class="bx bx-package" style="color:#696cff;"></i>
                        <div class="level-title">Product</div>
                        <div class="level-desc">All variations of one product</div>
                    </div>
                    {{-- Variation --}}
                    <div class="level-option" data-level="variation" onclick="selectLevel('variation')">
                        <i class="bx bx-barcode" style="color:#fd7e14;"></i>
                        <div class="level-title">Variation</div>
                        <div class="level-desc">One specific size/color SKU</div>
                    </div>
                </div>
                <input type="hidden" name="level" id="level_input" value="product">

                {{-- Cascade info --}}
                <div class="cascade-info mt-3" id="cascade_info_category" style="display:none;">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Category level:</strong> This discount will apply to <em>all products</em> in the selected category and all their <em>variations</em>.
                </div>
                <div class="cascade-info mt-3" id="cascade_info_product">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Product level:</strong> This discount will apply to <em>all size/color variations</em> of the selected product.
                </div>
                <div class="cascade-info mt-3" id="cascade_info_variation" style="display:none;">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Variation level:</strong> This discount targets only the <em>exact SKU</em> you select.
                </div>
            </x-modern.card>

            {{-- Target Selection --}}
            <x-modern.card title="Target Selection" class="mb-4" icon="bx bx-target-lock">

                {{-- Category Dropdown --}}
                <div class="mb-3" id="field_category">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select select2">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="cat_hint">Choose the category to apply the discount to.</small>
                </div>

                {{-- Product Dropdown (AJAX) --}}
                <div class="mb-3 d-none" id="field_product">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-select select2">
                        <option value="">— Select category first —</option>
                    </select>
                    <small class="text-muted">Select a category above first, then choose a product.</small>
                </div>

                {{-- Variation Dropdown (AJAX) --}}
                <div class="mb-3 d-none" id="field_variation">
                    <label class="form-label">Variations (SKUs) <span class="text-danger">*</span></label>
                    <select name="variation_ids[]" id="variation_ids" class="select2 w-100" multiple="multiple">
                        <option value="">— Select product first —</option>
                    </select>
                    <small class="text-muted">Select a product above first, then choose the specific variation.</small>
                </div>
            </x-modern.card>

            {{-- Discount Name --}}
            <x-modern.card title="Discount Details" class="mb-4" icon="bx bx-detail">
                <div class="mb-3">
                    <x-modern.input label="Discount Name" name="name" placeholder="e.g. Eid Mega Sale 2026" required icon="bx bx-purchase-tag" :value="old('name')" />
                    <small class="text-muted">A descriptive label to identify this discount rule.</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="discount_percentage" id="discount_pct" class="form-control" step="0.01" min="0.01" max="100"
                                value="{{ old('discount_percentage') }}" placeholder="e.g. 15" oninput="updatePreview(this.value)" required>
                            <span class="input-group-text fw-bold">%</span>
                        </div>
                        <small class="text-muted">Enter a value between 0.01 and 100.</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div class="discount-preview-box w-100">
                            <div class="pct" id="preview_pct">0%</div>
                            <div class="label">OFF — Preview</div>
                        </div>
                    </div>
                </div>
            </x-modern.card>
        </div>

        {{-- ── Right Column ── --}}
        <div class="col-12 col-lg-4">

            {{-- Session Period --}}
            <x-modern.card title="Session Period" class="mb-4" icon="bx bx-calendar-range">
                <div class="alert alert-warning py-2 mb-3">
                    <i class="bx bx-error-circle me-1"></i>
                    <strong>Required:</strong> Both start and end dates must be set.
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    <small class="text-muted">When this discount becomes active.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    <small class="text-muted">When this discount expires.</small>
                </div>
            </x-modern.card>

            {{-- Status --}}
            <x-modern.card title="Visibility" class="mb-4" icon="bx bx-toggle-left">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="active_status" name="active_status" value="1" checked>
                    <label class="form-check-label" for="active_status">Active / Enabled</label>
                </div>
                <small class="text-muted d-block mt-1">Even if enabled, the discount only applies within the session dates.</small>
            </x-modern.card>

            <div class="d-grid gap-2">
                <x-modern.actions.button type="submit" label="Save Discount Rule" icon="bx bx-save" size="lg" />
                <x-modern.actions.button tag="a" href="{{ route('discounts.index') }}" actionType="cancel" outline />
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    // ── Level Switcher ──
    window.selectLevel = function(level) {
        $('#level_input').val(level);
        $('.level-option').removeClass('selected');
        $(`.level-option[data-level="${level}"]`).addClass('selected');

        // Show/hide cascade info
        $('#cascade_info_category, #cascade_info_product, #cascade_info_variation').hide();
        $(`#cascade_info_${level}`).show();

        // Show/hide target fields
        if (level === 'category') {
            $('#field_category').show();
            $('#field_product, #field_variation').addClass('d-none');
            // Category select — no "load products" needed
            $('#cat_hint').text('Choose the category to apply the discount to.');
        } else if (level === 'product') {
            $('#field_category').show();
            $('#field_product').removeClass('d-none');
            $('#field_variation').addClass('d-none');
            $('#cat_hint').text('First select a category, then choose the product below.');
        } else {
            $('#field_category').show();
            $('#field_product').removeClass('d-none');
            $('#field_variation').removeClass('d-none');
            $('#cat_hint').text('Select category → product → variation.');
        }
    };

    // ── AJAX: Load products when category changes ──
    $('#category_id').on('change', function() {
        const catId = $(this).val();
        const level = $('#level_input').val();

        if (catId && level !== 'category') {
            $.get('/back/discounts/products/' + catId, function(data) {
                $('#product_id').empty().append('<option value="">Select Product</option>');
                $.each(data, function(i, p) {
                    $('#product_id').append(`<option value="${p.id}">${p.name}</option>`);
                });
                $('#product_id').trigger('change.select2');
            });
        }
        $('#variation_ids').empty();
    });

    // ── AJAX: Load variations when product changes ──
    $('#product_id').on('change', function() {
        const pId = $(this).val();
        const level = $('#level_input').val();

        if (pId && level === 'variation') {
            $.get('/back/discounts/variations/' + pId, function(data) {
                $('#variation_ids').empty();
                $.each(data, function(i, v) {
                    $('#variation_ids').append(`<option value="${v.id}">${v.sku}</option>`);
                });
                $('#variation_ids').trigger('change.select2');
            });
        }
    });

    // ── Live Preview ──
    window.updatePreview = function(val) {
        const v = parseFloat(val) || 0;
        $('#preview_pct').text(v.toFixed(1) + '%');
    };

    // Initialise correct level view
    selectLevel($('#level_input').val() || 'product');
});
</script>
@endpush
