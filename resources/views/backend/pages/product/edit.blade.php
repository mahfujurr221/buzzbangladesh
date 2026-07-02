@extends('backend.layouts.master')

@section('title', 'Edit Product')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <select class="form-select" name="category_id" id="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
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
                        <select class="form-select" name="brand_id" id="brand_id">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
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
                    <textarea class="form-control" name="description" rows="5">{{ $product->description }}</textarea>
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
                        <select class="form-select select2" name="selected_colors[]" id="selected_colors" multiple>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" data-name="{{ $color->name }}" {{ in_array($color->id, $selectedColorIds) ? 'selected' : '' }}>{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Available Sizes</label>
                        <select class="form-select select2" name="selected_sizes[]" id="selected_sizes" multiple>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}" data-name="{{ $size->name }}" {{ in_array($size->id, $selectedSizeIds) ? 'selected' : '' }}>{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-2 text-end">
                    <button type="button" class="btn btn-warning btn-sm" id="generateVariationsBtn">
                        <i class="bx bx-refresh me-1"></i> Re-Generate Matrix (Will Wipe Below)
                    </button>
                    <button type="button" class="btn btn-primary btn-sm ms-2" id="addVariationRowBtn">
                        <i class="bx bx-plus me-1"></i> Add Custom Row
                    </button>
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
                    <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('fileInput').click()">Browse Files</button>
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
            <x-modern.card title="Base Pricing & Stock" class="mb-4">
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
                <button type="submit" class="btn btn-info btn-lg"><i class="bx bx-save me-2"></i> Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    const availableColors = @json($colors);
    
    $(document).ready(function() {
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
    });
</script>
@endpush
