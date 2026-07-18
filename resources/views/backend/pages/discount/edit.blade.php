@extends('backend.layouts.master')

@section('title', 'Edit Discount Rule')

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
    .level-option:hover { border-color: #696cff; background: #f4f5fa; }
    .level-option.selected {
        border-color: #696cff;
        background: linear-gradient(135deg, #f4f5fa, #e9eaff);
        box-shadow: 0 4px 15px rgba(105,108,255,.15);
    }
    .level-option i { font-size: 28px; margin-bottom: 8px; display: block; }
    .level-option .level-title { font-weight: 700; font-size: 14px; color: #566a7f; }
    .level-option .level-desc  { font-size: 11px; color: #a1acb8; margin-top: 4px; }
    .level-option.selected .level-title,
    .level-option.selected .level-desc { color: #696cff; }
    .level-option.selected i { color: #696cff; }
    .discount-preview-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: white;
        padding: 24px;
        text-align: center;
    }
    .discount-preview-box .pct { font-size: 52px; font-weight: 900; line-height: 1; }
    .discount-preview-box .label { font-size: 14px; opacity: .85; margin-top: 6px; }
    .cascade-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 13px;
        color: #0369a1;
    }
</style>
@endpush

@section('content')
<form action="{{ route('discounts.update', $discount->id) }}" method="POST" id="discountForm">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- ── Left Column ── --}}
        <div class="col-12 col-lg-8">

            {{-- Level --}}
            <x-modern.card title="Discount Level" class="mb-4" icon="bx bx-layer">
                <p class="text-muted mb-3">Choose how this discount will be applied in the hierarchy.</p>
                <div class="level-selector">
                    <div class="level-option {{ $discount->level === 'category' ? 'selected' : '' }}" data-level="category" onclick="selectLevel('category')">
                        <i class="bx bx-grid-alt" style="color:#7c3aed;"></i>
                        <div class="level-title">Category</div>
                        <div class="level-desc">All products + all variations in a category</div>
                    </div>
                    <div class="level-option {{ $discount->level === 'product' ? 'selected' : '' }}" data-level="product" onclick="selectLevel('product')">
                        <i class="bx bx-package" style="color:#696cff;"></i>
                        <div class="level-title">Product</div>
                        <div class="level-desc">All variations of one product</div>
                    </div>
                    <div class="level-option {{ $discount->level === 'variation' ? 'selected' : '' }}" data-level="variation" onclick="selectLevel('variation')">
                        <i class="bx bx-barcode" style="color:#fd7e14;"></i>
                        <div class="level-title">Variation</div>
                        <div class="level-desc">One specific size/color SKU</div>
                    </div>
                </div>
                <input type="hidden" name="level" id="level_input" value="{{ $discount->level }}">

                <div class="cascade-info mt-3" id="cascade_info_category" style="{{ $discount->level !== 'category' ? 'display:none;' : '' }}">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Category level:</strong> This discount will apply to <em>all products</em> in the selected category and all their <em>variations</em>.
                </div>
                <div class="cascade-info mt-3" id="cascade_info_product" style="{{ $discount->level !== 'product' ? 'display:none;' : '' }}">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Product level:</strong> This discount will apply to <em>all size/color variations</em> of the selected product.
                </div>
                <div class="cascade-info mt-3" id="cascade_info_variation" style="{{ $discount->level !== 'variation' ? 'display:none;' : '' }}">
                    <i class='bx bx-info-circle me-1'></i>
                    <strong>Variation level:</strong> This discount targets only the <em>exact SKU</em> you select.
                </div>
            </x-modern.card>

            {{-- Target --}}
            <x-modern.card title="Target Selection" class="mb-4" icon="bx bx-target-lock">
                <div class="mb-3" id="field_category">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select select2">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $discount->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 {{ in_array($discount->level, ['product','variation']) ? '' : 'd-none' }}" id="field_product">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-select select2">
                        <option value="">Select Product</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ $discount->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 {{ $discount->level === 'variation' ? '' : 'd-none' }}" id="field_variation">
                    <label class="form-label">Variations (SKUs) <span class="text-danger">*</span></label>
                    <select name="variation_ids[]" id="variation_ids" class="form-select select2" multiple="multiple">
                        <option value="">Select Variation</option>
                        @foreach($variations as $v)
                            @php
                                $label = $v->sku;
                                if($v->size) $label .= ' — '.$v->size->name;
                                if($v->color) $label .= ' / '.$v->color->name;
                            @endphp
                            <option value="{{ $v->id }}" {{ in_array($v->id, old('variation_ids', $discount->variation_ids ?? [])) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </x-modern.card>

            {{-- Details --}}
            <x-modern.card title="Discount Details" class="mb-4" icon="bx bx-detail">
                <div class="mb-3">
                    <x-modern.input label="Discount Name" name="name" placeholder="e.g. Eid Mega Sale 2026" required icon="bx bx-purchase-tag" :value="old('name', $discount->name)" />
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="discount_percentage" id="discount_pct" class="form-control" step="0.01" min="0.01" max="100"
                                value="{{ old('discount_percentage', $discount->discount_percentage) }}" oninput="updatePreview(this.value)" required>
                            <span class="input-group-text fw-bold">%</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div class="discount-preview-box w-100">
                            <div class="pct" id="preview_pct">{{ number_format($discount->discount_percentage, 1) }}%</div>
                            <div class="label">OFF — Preview</div>
                        </div>
                    </div>
                </div>
            </x-modern.card>
        </div>

        {{-- ── Right Column ── --}}
        <div class="col-12 col-lg-4">
            <x-modern.card title="Session Period" class="mb-4" icon="bx bx-calendar-range">
                <div class="alert alert-warning py-2 mb-3">
                    <i class="bx bx-error-circle me-1"></i>
                    <strong>Required:</strong> Both start and end dates must be set.
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $discount->start_date?->format('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $discount->end_date?->format('Y-m-d')) }}" required>
                </div>
            </x-modern.card>

            <x-modern.card title="Visibility" class="mb-4" icon="bx bx-toggle-left">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="active_status" name="active_status" value="1"
                        {{ $discount->active_status ? 'checked' : '' }}>
                    <label class="form-check-label" for="active_status">Active / Enabled</label>
                </div>
                <small class="text-muted d-block mt-1">Even if enabled, the discount only applies within the session dates.</small>
            </x-modern.card>

            <div class="d-grid gap-2">
                <x-modern.actions.button type="submit" label="Update Discount Rule" icon="bx bx-save" size="lg" />
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
    $('.select2').select2({ theme: 'bootstrap-5' });

    window.selectLevel = function(level) {
        $('#level_input').val(level);
        $('.level-option').removeClass('selected');
        $(`.level-option[data-level="${level}"]`).addClass('selected');
        $('#cascade_info_category, #cascade_info_product, #cascade_info_variation').hide();
        $(`#cascade_info_${level}`).show();

        if (level === 'category') {
            $('#field_category').show();
            $('#field_product, #field_variation').addClass('d-none');
        } else if (level === 'product') {
            $('#field_category').show();
            $('#field_product').removeClass('d-none');
            $('#field_variation').addClass('d-none');
        } else {
            $('#field_category, #field_product, #field_variation').show().removeClass('d-none');
        }
    };

    $('#category_id').on('change', function() {
        const catId = $(this).val();
        const level = $('#level_input').val();
        if (catId && level !== 'category') {
            $.get('/back/discounts/products/' + catId, function(data) {
                $('#product_id').empty().append('<option value="">Select Product</option>');
                $.each(data, function(i, p) {
                    $('#product_id').append(`<option value="${p.id}">${p.name}</option>`);
                });
            });
        }
        $('#variation_ids').empty();
    });

    $('#product_id').on('change', function() {
        const pId = $(this).val();
        const level = $('#level_input').val();
        if (pId && level === 'variation') {
            $.get('/back/discounts/variations/' + pId, function(data) {
                $('#variation_ids').empty();
                $.each(data, function(i, v) {
                    $('#variation_ids').append(`<option value="${v.id}">${v.sku}</option>`);
                });
            });
        }
    });

    window.updatePreview = function(val) {
        const v = parseFloat(val) || 0;
        $('#preview_pct').text(v.toFixed(1) + '%');
    };
});
</script>
@endpush
