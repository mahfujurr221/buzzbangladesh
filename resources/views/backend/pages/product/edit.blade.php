@extends('backend.layouts.master')

@section('title', 'Edit Product')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('backend/css/summernote/summernote-bs5.min.css') }}" rel="stylesheet">
<style>
    .image-preview-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 20px;
    }
    .image-preview-item {
        position: relative;
        flex: 0 0 calc(25% - 12px); /* 4 items per row */
        max-width: calc(25% - 12px);
        border: 1px solid #e4e6eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        cursor: grab;
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
    }
    .image-preview-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .image-preview-item:active {
        cursor: grabbing;
    }
    .image-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-bottom: 1px solid #f0f2f4;
    }
    .image-actions {
        padding: 10px;
        background: #fff;
    }
    .remove-image {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(255, 77, 73, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: background 0.2s;
        z-index: 10;
    }
    .remove-image:hover {
        background: rgba(255, 77, 73, 1);
    }
    .main-image-label {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 6px;
        cursor: pointer;
    }
    .color-select {
        width: 100%;
        padding: 4px 8px;
        font-size: 12px;
        border: 1px solid #d9dee3;
        border-radius: 4px;
        background-color: #f8f9fa;
        color: #566a7f;
    }
    /* Responsive breakpoints */
    @media (max-width: 991px) {
        .image-preview-item {
            flex: 0 0 calc(33.333% - 10.66px);
            max-width: calc(33.333% - 10.66px);
        }
    }
    @media (max-width: 767px) {
        .image-preview-item {
            flex: 0 0 calc(50% - 8px);
            max-width: calc(50% - 8px);
        }
    }
    @media (max-width: 480px) {
        .image-preview-item {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    .dropzone-area {
        border: 2px dashed #696cff;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        background: #f4f5fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .dropzone-area.dragover {
        background: #e2e4ff;
        border-color: #5f61e6;
    }
</style>
@endpush

@section('content')
<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-12 col-lg-8">
            <!-- Basic Information -->
            <x-modern.card title="Basic Information" class="mb-4">
                <div class="mb-3">
                    <x-modern.input label="Product Name" name="name" value="{{ $product->name }}" placeholder="Enter Product Name" required icon="bx bx-purchase-tag" />
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <select class="form-select select2" name="category_id" id="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                            <x-modern.actions.button type="button" icon="bx bx-plus" data-bs-toggle="modal" data-bs-target="#addCategoryModal" />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <select class="form-select" name="sub_category_id" id="sub_category_id">
                            <option value="">Select Sub Category</option>
                            @foreach($subCategories as $sub)
                                <option value="{{ $sub->id }}" {{ $product->sub_category_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand</label>
                        <div class="d-flex gap-2">
                            <select class="form-select select2" name="brand_id" id="brand_id">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                            <x-modern.actions.button type="button" icon="bx bx-plus" data-bs-toggle="modal" data-bs-target="#addBrandModal" />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="active_status" name="active_status" value="1" {{ $product->active_status ? 'checked' : '' }}>
                            <label class="form-check-label" for="active_status">Active / Published</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-control" name="short_description" rows="2">{{ $product->short_description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Description</label>
                    <textarea class="form-control summernote" name="description" rows="5">{{ $product->description }}</textarea>
                </div>
            </x-modern.card>

            <!-- Variations Matrix -->
            <x-modern.card title="Product Variations (Sizes & Colors)" class="mb-4">
                @php
                    $selectedColorIds = $product->variations->pluck('product_color_id')->filter()->unique()->toArray();
                    $selectedSizeIds = $product->variations->pluck('product_size_id')->filter()->unique()->toArray();
                @endphp
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Available Colors</label>
                        <div class="d-flex gap-2">
                        <select class="form-select select2" name="selected_colors[]" id="selected_colors" multiple>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" data-name="{{ $color->name }}" {{ in_array($color->id, $selectedColorIds) ? 'selected' : '' }}>{{ $color->name }}</option>
                            @endforeach
                        </select>
                            <x-modern.actions.button type="button" icon="bx bx-plus" data-bs-toggle="modal" data-bs-target="#addColorModal" />
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Available Sizes</label>
                        <div class="d-flex gap-2">
                        <select class="form-select select2" name="selected_sizes[]" id="selected_sizes" multiple>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}" data-name="{{ $size->name }}" {{ in_array($size->id, $selectedSizeIds) ? 'selected' : '' }}>{{ $size->name }}</option>
                            @endforeach
                        </select>
                            <x-modern.actions.button type="button" icon="bx bx-plus" data-bs-toggle="modal" data-bs-target="#addSizeModal" />
                        </div>
                    </div>
                </div>
                
                <div class="mt-2 text-end d-flex justify-content-end gap-2">
                    <x-modern.actions.button type="button" id="generateVariationsBtn" label="Re-Generate Matrix (Will Wipe Below)" icon="bx bx-refresh" size="sm" variant="warning" />
                    <x-modern.actions.button type="button" id="addVariationRowBtn" label="Add Custom Row" icon="bx bx-plus" size="sm" />
                </div>
                
                <hr>
                
                <div class="table-responsive mt-3 {{ $product->variations->count() == 0 ? 'd-none' : '' }}" id="variationsTableWrapper">
                    <table class="table table-bordered text-center align-middle" id="variationsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Variant</th>
                                <th>SKU</th>
                                <th>Price (৳)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variations as $index => $var)
                                @php
                                    $variantName = '';
                                    if($var->color) $variantName .= $var->color->name;
                                    if($var->color && $var->size) $variantName .= ' - ';
                                    if($var->size) $variantName .= $var->size->name;
                                    if($variantName == '') $variantName = 'Default';
                                @endphp
                                <tr>
                                    <td class="fw-bold">
                                        {{ $variantName }}
                                        <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $var->id }}">
                                        <input type="hidden" name="variations[{{ $index }}][size_id]" value="{{ $var->product_size_id }}">
                                        <input type="hidden" name="variations[{{ $index }}][color_id]" value="{{ $var->product_color_id }}">
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm text-center" name="variations[{{ $index }}][sku]" value="{{ $var->sku }}" required></td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm text-center" name="variations[{{ $index }}][price]" value="{{ $var->sale_price }}" required></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-icon rounded-circle remove-row-btn"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center text-muted p-4 {{ $product->variations->count() > 0 ? 'd-none' : '' }}" id="variationsEmptyState">
                    No variations generated. Use the buttons above.
                </div>
            </x-modern.card>

            <!-- Media & Images -->
            <x-modern.card title="Product Images" class="mb-4">
                <div class="dropzone-area" id="dropzoneArea">
                    <i class="bx bx-upload fs-1 text-primary mb-2"></i>
                    <h5 class="mb-1">Drag and drop images here</h5>
                    <p class="text-muted mb-2">or click to browse from your computer</p>
                    <input type="file" id="fileInput" name="images[]" multiple accept="image/*" class="d-none">
                    <x-modern.actions.button type="button" size="sm" onclick="document.getElementById('fileInput').click()" label="Browse Files" />
                </div>
                
                @php
                    $mainImageName = '';
                    foreach($product->images as $img) {
                        if($img->is_main) $mainImageName = 'existing_'.$img->id;
                    }
                @endphp
                <!-- Hidden inputs -->
                <input type="hidden" name="main_image_name" id="main_image_name" value="{{ $mainImageName }}">
                <input type="hidden" name="image_sort_order" id="image_sort_order" value="">
                <input type="hidden" name="deleted_images" id="deleted_images" value="[]">
                
                <!-- Container for previews -->
                <div class="image-preview-wrapper" id="imagePreviewContainer">
                    @foreach($product->images->sortBy('sort_order') as $img)
                        <div class="image-preview-item existing-image" data-id="{{ $img->id }}" data-filename="existing_{{ $img->id }}">
                            <input type="hidden" name="existing_images[{{ $img->id }}][id]" value="{{ $img->id }}">
                            
                            <button type="button" class="remove-image" onclick="removeExistingImage({{ $img->id }}, this)" title="Remove Image">
                                <i class="bx bx-x"></i>
                            </button>
                            <img src="{{ asset($img->image_path) }}">
                            <div class="image-actions">
                                <label class="main-image-label text-primary fw-bold">
                                    <input type="radio" name="main_image_radio" value="existing_{{ $img->id }}" onchange="setMainImage('existing_{{ $img->id }}')" {{ $img->is_main ? 'checked' : '' }}>
                                    Set as Main
                                </label>
                                <select class="color-select" name="existing_images[{{ $img->id }}][color_id]">
                                    <option value="">No Color</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}" {{ $img->product_color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bx bx-info-circle me-1"></i> You can select a color for each image. <strong>Tip:</strong> You can drag and drop the images to reorder them!
                </div>
            </x-modern.card>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Pricing Information -->
            <x-modern.card title="Base Pricing" class="mb-4">
                <div class="mb-3">
                    <x-modern.input label="Sale Price (৳)" name="sale_price" id="base_sale_price" value="{{ $product->sale_price }}" type="number" step="0.01" placeholder="0.00" required icon="bx bx-money" />
                    <small class="text-muted">This price will be applied to all new variations by default.</small>
                </div>
                <div class="mb-3">
                    <x-modern.input label="Purchase Price (৳)" name="purchase_price" value="{{ $product->purchase_price }}" type="number" step="0.01" placeholder="0.00" icon="bx bx-cart-alt" />
                    <small class="text-muted">Internal cost for profit calculation.</small>
                </div>
                @php
                    $defaultSku = $product->variations->first() ? $product->variations->first()->sku : '';
                    $defaultStock = $product->variations->first() ? $product->variations->first()->stock_quantity : 0;
                @endphp
                <div class="mb-3">
                    <x-modern.input label="Default SKU" name="sku" id="base_sku" value="{{ $defaultSku }}" placeholder="e.g. TSHIRT-BLK-M" required icon="bx bx-barcode" />
                </div>
            </x-modern.card>

            <!-- Product Labels -->
            <x-modern.card title="Product Labels" icon="bx bx-purchase-tag" class="mb-4">
                <small class="text-muted d-block mb-3">Tag this product to appear in special sections on the storefront.</small>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="is_new_arrival" {{ $product->is_new_arrival ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center gap-2" for="is_new_arrival">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">New Arrival</span>
                            <small class="text-muted">Recently added stock</small>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center gap-2" for="is_featured">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Featured</span>
                            <small class="text-muted">Highlighted pick</small>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="is_best_seller" {{ $product->is_best_seller ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center gap-2" for="is_best_seller">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Best Seller</span>
                            <small class="text-muted">Top selling product</small>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_on_sale" value="1" id="is_on_sale" {{ $product->is_on_sale ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center gap-2" for="is_on_sale">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">On Sale</span>
                            <small class="text-muted">Has an active deal</small>
                        </label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_trending" value="1" id="is_trending" {{ $product->is_trending ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center gap-2" for="is_trending">
                            <span class="badge px-2 py-1" style="background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">Trending</span>
                            <small class="text-muted">Viral / high demand</small>
                        </label>
                    </div>
                </div>
            </x-modern.card>

            <!-- Entry Date & Season -->
            <x-modern.card title="Entry Date & Season" icon="bx bx-calendar-star" class="mb-4">
                <div class="mb-3">
                    <label class="form-label">Stock Entry Date</label>
                    <input type="date" name="entry_date" class="form-control" value="{{ $product->entry_date?->format('Y-m-d') }}">
                    <small class="text-muted">The date this stock was physically entered into inventory.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Season</label>
                    <div class="d-flex gap-2">
                        <select name="season_id" id="season_id" class="form-select select2">
                            <option value="">No Season</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}" {{ $product->season_id == $season->id ? 'selected' : '' }}>{{ $season->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary btn-icon flex-shrink-0"
                            data-bs-toggle="modal" data-bs-target="#addSeasonModal" title="Add Season">
                            <i class="bx bx-plus"></i>
                        </button>
                    </div>
                    <small class="text-muted">Assign to a seasonal collection (Eid, Summer, etc.).</small>
                </div>
            </x-modern.card>

            <!-- SEO Information -->
            <x-modern.card title="SEO Settings" class="mb-4">
                <div class="mb-3">
                    <x-modern.input label="SEO Title" name="seo_title" value="{{ $product->seo_title }}" placeholder="Meta Title" icon="bx bx-search" />
                </div>
                <div class="mb-3">
                    <label class="form-label">SEO Description</label>
                    <textarea class="form-control" name="seo_description" rows="3">{{ $product->seo_description }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tags / Keywords</label>
                    <input type="text" class="form-control" name="seo_tags" value="{{ $product->seo_tags }}" placeholder="e.g. aesthetic, y2k, oversized">
                </div>
            </x-modern.card>

            <div class="d-grid gap-2">
                <x-modern.actions.button type="submit" label="Update Product" icon="bx bx-save" size="lg" variant="info" />
                <x-modern.actions.button tag="a" href="{{ route('products.index') }}" actionType="cancel" outline />
            </div>
        </div>
    </div>
</form>



<!-- Quick Add Modals -->
<!-- Category Modal -->
<x-modern.modal id="addCategoryModal" title="Add Category">
    <form class="quick-add-form" action="{{ route('categories.store') }}" method="POST" data-target-select="#category_id" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" checked>
            <label class="form-check-label">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" class="btn-save" />
        </div>
    </form>
</x-modern.modal>

<!-- Brand Modal -->
<x-modern.modal id="addBrandModal" title="Add Brand">
    <form class="quick-add-form" action="{{ route('brands.store') }}" method="POST" data-target-select="#brand_id" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" checked>
            <label class="form-check-label">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" class="btn-save" />
        </div>
    </form>
</x-modern.modal>

<!-- Color Modal -->
<x-modern.modal id="addColorModal" title="Add Color">
    <form class="quick-add-form" action="{{ route('colors.store') }}" method="POST" data-target-select="#selected_colors">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. Red">
        </div>
        <div class="mb-3">
            <label class="form-label">Color Code (Hex/Text)</label>
            <input type="text" name="code" class="form-control" required placeholder="e.g. #FF0000 or red">
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" checked>
            <label class="form-check-label">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" class="btn-save" />
        </div>
    </form>
</x-modern.modal>

<!-- Size Modal -->
<x-modern.modal id="addSizeModal" title="Add Size">
    <form class="quick-add-form" action="{{ route('sizes.store') }}" method="POST" data-target-select="#selected_sizes">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. XL">
        </div>
        <div class="mb-3">
            <label class="form-label">Body Size (Optional)</label>
            <input type="text" name="body_size" class="form-control" placeholder="e.g. 42-44">
        </div>
        <div class="mb-3">
            <label class="form-label">Height (Optional)</label>
            <input type="text" name="height" class="form-control" placeholder="e.g. 30">
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" checked>
            <label class="form-check-label">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" class="btn-save" />
        </div>
    </form>
</x-modern.modal>

<!-- Quick Add Season Modal -->
<x-modern.modal id="addSeasonModal" title="Add Season">
    <form class="quick-add-form" action="{{ route('seasons.store') }}" method="POST" data-target-select="#season_id">
        @csrf
        <div class="mb-3">
            <label class="form-label">Season Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. Eid-ul-Fitr 2026">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control">
            </div>
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="active_status" value="1" checked>
            <label class="form-check-label">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <x-modern.actions.button type="button" actionType="cancel" outline data-bs-dismiss="modal" />
            <x-modern.actions.button type="submit" actionType="save" class="btn-save" />
        </div>
    </form>
</x-modern.modal>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('backend/js/summernote/summernote-bs5.min.js') }}"></script>

<script>
    const availableColors = @json($colors);
    
    $(document).ready(function() {
        // Initialize Summernote
        $('.summernote').summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.select2').select2({ theme: 'bootstrap-5' });

        $('#selected_colors').on('change', function() {
            let selectedColors = [];
            $('#selected_colors option:selected').each(function() {
                selectedColors.push({ id: $(this).val(), name: $(this).data('name') });
            });
            
            $('.color-select').each(function() {
                let currentVal = $(this).val();
                $(this).empty();
                $(this).append('<option value="">No Color</option>');
                selectedColors.forEach(c => {
                    let selected = (c.id == currentVal) ? 'selected' : '';
                    $(this).append(`<option value="${c.id}" ${selected}>${c.name}</option>`);
                });
            });
        });
        
        // Trigger on load to filter existing image color dropdowns
        $('#selected_colors').trigger('change');

        $('#category_id').change(function() {
            var catId = $(this).val();
            if(catId) {
                $.ajax({
                    url: '/get-subcategories/' + catId,
                    type: 'GET',
                    success: function(data) {
                        $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $('#sub_category_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    }
                });
            } else {
                $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
            }
        });

        const fileInput = document.getElementById('fileInput');
        const dropzone = document.getElementById('dropzoneArea');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const mainImageInput = document.getElementById('main_image_name');
        const deletedImagesInput = document.getElementById('deleted_images');
        
        let dataTransfer = new DataTransfer();

        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });
        
        fileInput.addEventListener('change', function() { handleFiles(this.files); });

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if(!file.type.match('image.*')) return;
                dataTransfer.items.add(file);
                const reader = new FileReader();
                reader.onload = (e) => { createPreviewElement(e.target.result, file.name); }
                reader.readAsDataURL(file);
            });
            fileInput.files = dataTransfer.files;
        }

        function createPreviewElement(src, filename) {
            let colorOptions = '<option value="">No Color</option>';
            $('#selected_colors option:selected').each(function() {
                colorOptions += `<option value="${$(this).val()}">${$(this).data('name')}</option>`;
            });

            if(mainImageInput.value === '') { mainImageInput.value = filename; }
            let isMain = (mainImageInput.value === filename);
            let checkedAttr = isMain ? 'checked' : '';

            const item = document.createElement('div');
            item.className = 'image-preview-item';
            item.dataset.filename = filename;
            
            item.innerHTML = `
                <button type="button" class="remove-image" onclick="removeImage('${filename}', this)" title="Remove Image"><i class="bx bx-x"></i></button>
                <img src="${src}">
                <div class="image-actions">
                    <label class="main-image-label text-primary fw-bold">
                        <input type="radio" name="main_image_radio" value="${filename}" onchange="setMainImage('${filename}')" ${checkedAttr}>
                        Set as Main
                    </label>
                    <select class="color-select" name="image_colors[${filename}]">
                        ${colorOptions}
                    </select>
                </div>
            `;
            previewContainer.appendChild(item);
        }

        window.removeImage = function(filename, btnElement) {
            btnElement.closest('.image-preview-item').remove();
            const newDataTransfer = new DataTransfer();
            Array.from(dataTransfer.files).forEach(file => {
                if(file.name !== filename) newDataTransfer.items.add(file);
            });
            dataTransfer = newDataTransfer;
            fileInput.files = dataTransfer.files;

            if(mainImageInput.value === filename) {
                mainImageInput.value = '';
                const firstRadio = document.querySelector('input[name="main_image_radio"]');
                if(firstRadio) {
                    firstRadio.checked = true;
                    mainImageInput.value = firstRadio.value;
                }
            }
        };

        window.removeExistingImage = function(id, btnElement) {
            let deletedArray = JSON.parse(deletedImagesInput.value);
            deletedArray.push(id);
            deletedImagesInput.value = JSON.stringify(deletedArray);
            
            let filename = 'existing_' + id;
            btnElement.closest('.image-preview-item').remove();
            
            if(mainImageInput.value === filename) {
                mainImageInput.value = '';
                const firstRadio = document.querySelector('input[name="main_image_radio"]');
                if(firstRadio) {
                    firstRadio.checked = true;
                    mainImageInput.value = firstRadio.value;
                }
            }
        };

        window.setMainImage = function(filename) {
            mainImageInput.value = filename;
        };

        new Sortable(previewContainer, { animation: 150, ghostClass: 'bg-light' });

        $('#productForm').submit(function() {
            let order = [];
            $('.image-preview-item').each(function() {
                order.push($(this).data('filename'));
            });
            $('#image_sort_order').val(JSON.stringify(order));
        });
        
        let indexCounter = {{ $product->variations->count() > 0 ? $product->variations->count() : 0 }};
        
        $('#generateVariationsBtn').click(function() {
            if(!confirm('This will wipe all existing rows below. Proceed?')) return;
            
            let colors = [];
            let sizes = [];
            
            $('#selected_colors option:selected').each(function() { colors.push({ id: $(this).val(), name: $(this).data('name') }); });
            $('#selected_sizes option:selected').each(function() { sizes.push({ id: $(this).val(), name: $(this).data('name') }); });
            
            const tbody = $('#variationsTable tbody');
            tbody.empty();
            
            let basePrice = $('#base_sale_price').val();
            let baseSku = $('#base_sku').val() || 'SKU';
            
            if(colors.length === 0 && sizes.length === 0) {
                toast('Please select at least one color or size.', 'error');
                return;
            }
            
            $('#variationsEmptyState').addClass('d-none');
            $('#variationsTableWrapper').removeClass('d-none');
            
            if(colors.length > 0 && sizes.length === 0) {
                colors.forEach(c => appendRow(indexCounter++, c.name, null, c.id, basePrice, baseSku + '-' + c.name));
            } else if(sizes.length > 0 && colors.length === 0) {
                sizes.forEach(s => appendRow(indexCounter++, s.name, s.id, null, basePrice, baseSku + '-' + s.name));
            } else {
                colors.forEach(c => {
                    sizes.forEach(s => {
                        let variantName = c.name + ' - ' + s.name;
                        let skuSuffix = c.name.substring(0,3).toUpperCase() + '-' + s.name;
                        appendRow(indexCounter++, variantName, s.id, c.id, basePrice, baseSku + '-' + skuSuffix);
                    });
                });
            }
        });

        $('#addVariationRowBtn').click(function() {
            $('#variationsEmptyState').addClass('d-none');
            $('#variationsTableWrapper').removeClass('d-none');
            appendRow(indexCounter++, 'Custom', null, null, $('#base_sale_price').val(), $('#base_sku').val() + '-CUST');
        });

        function appendRow(index, variantName, sizeId, colorId, price, sku) {
            let html = `
                <tr>
                    <td class="fw-bold">
                        ${variantName}
                        <input type="hidden" name="variations[${index}][size_id]" value="${sizeId || ''}">
                        <input type="hidden" name="variations[${index}][color_id]" value="${colorId || ''}">
                    </td>
                    <td><input type="text" class="form-control form-control-sm text-center" name="variations[${index}][sku]" value="${sku}" required></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm text-center" name="variations[${index}][price]" value="${price}" required></td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm btn-icon rounded-circle remove-row-btn"><i class="bx bx-trash"></i></button></td>
                </tr>
            `;
            $('#variationsTable tbody').append(html);
        }

        $(document).on('click', '.remove-row-btn', function() {
            $(this).closest('tr').remove();
            if($('#variationsTable tbody tr').length === 0) {
                $('#variationsEmptyState').removeClass('d-none');
                $('#variationsTableWrapper').addClass('d-none');
            }
        });
        // Handle Quick Add AJAX forms
        $('.quick-add-form').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('.btn-save');
            let targetSelect = form.data('target-select');
            
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Saving...');
            
            let formData = new FormData(this);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status === 'success') {
                        let data = response.data;
                        let select = $(targetSelect);
                        
                        let newOption = new Option(data.name, data.id, true, true);
                        $(newOption).attr('data-name', data.name);
                        select.append(newOption).trigger('change');
                        
                        form.trigger('reset');
                        form.closest('.modal').modal('hide');
                        
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    let msg = 'An error occurred';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                },
                complete: function() {
                    btn.prop('disabled', false).html('Save changes');
                }
            });
        });
    });
</script>
@endpush
