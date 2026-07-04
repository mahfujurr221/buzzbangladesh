@extends('backend.layouts.master')

@section('title', 'Add New Product')

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
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="row">
        <div class="col-12 col-lg-8">
            <!-- Basic Information -->
            <x-modern.card title="Basic Information" class="mb-4">
                <div class="mb-3">
                    <x-modern.input label="Product Name" name="name" placeholder="Enter Product Name (e.g. Vintage Oversized Tee)" required icon="bx bx-purchase-tag" />
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <select class="form-select select2" name="category_id" id="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="bx bx-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <select class="form-select" name="sub_category_id" id="sub_category_id">
                            <option value="">Select Sub Category</option>
                            <!-- Loaded via AJAX -->
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
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addBrandModal"><i class="bx bx-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="active_status" name="active_status" value="1" checked>
                            <label class="form-check-label" for="active_status">Active / Published</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-control" name="short_description" rows="2" placeholder="Brief summary for product card..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Description</label>
                    <textarea class="form-control" name="description" rows="5" placeholder="Detailed product information..."></textarea>
                </div>
            </x-modern.card>

            <!-- Variations Matrix -->
            <x-modern.card title="Product Variations (Sizes & Colors)" class="mb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Available Colors</label>
                        <div class="d-flex gap-2">
                        <select class="form-select select2" name="selected_colors[]" id="selected_colors" multiple>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" data-name="{{ $color->name }}" selected>{{ $color->name }}</option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addColorModal"><i class="bx bx-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Available Sizes</label>
                        <div class="d-flex gap-2">
                        <select class="form-select select2" name="selected_sizes[]" id="selected_sizes" multiple>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}" data-name="{{ $size->name }}" selected>{{ $size->name }}</option>
                            @endforeach
                        </select>
                            <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addSizeModal"><i class="bx bx-plus"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-2 text-end">
                    <button type="button" class="btn btn-primary btn-sm" id="generateVariationsBtn">
                        <i class="bx bx-refresh me-1"></i> Generate Variation Matrix
                    </button>
                </div>
                
                <hr>
                
                <div class="table-responsive mt-3 d-none" id="variationsTableWrapper">
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
                            <!-- Rows generated by JS -->
                        </tbody>
                    </table>
                </div>
                <div class="text-center text-muted p-4" id="variationsEmptyState">
                    Select colors and sizes above, then click Generate to create your stock matrix.
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
                
                <!-- Hidden input to track main image -->
                <input type="hidden" name="main_image_name" id="main_image_name" value="">
                <!-- Hidden input to track sort order -->
                <input type="hidden" name="image_sort_order" id="image_sort_order" value="">
                
                <!-- Container for JS rendered previews -->
                <div class="image-preview-wrapper" id="imagePreviewContainer">
                    <!-- Images will be injected here via JS -->
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
                    <x-modern.input label="Sale Price (৳)" name="sale_price" id="base_sale_price" type="number" step="0.01" placeholder="0.00" required icon="bx bx-money" />
                    <small class="text-muted">This price will be applied to all variations by default.</small>
                </div>
                <div class="mb-3">
                    <x-modern.input label="Purchase Price (৳)" name="purchase_price" type="number" step="0.01" placeholder="0.00" icon="bx bx-cart-alt" />
                    <small class="text-muted">Internal cost for profit calculation.</small>
                </div>
                <div class="mb-3">
                    <x-modern.input label="Default SKU" name="sku" id="base_sku" placeholder="e.g. TSHIRT-BLK-M" required icon="bx bx-barcode" />
                </div>
            </x-modern.card>

            <!-- SEO Information -->
            <x-modern.card title="SEO Settings" class="mb-4">
                <div class="mb-3">
                    <x-modern.input label="SEO Title" name="seo_title" placeholder="Meta Title" icon="bx bx-search" />
                </div>
                <div class="mb-3">
                    <label class="form-label">SEO Description</label>
                    <textarea class="form-control" name="seo_description" rows="3" placeholder="Meta Description"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tags / Keywords</label>
                    <input type="text" class="form-control" name="seo_tags" placeholder="e.g. aesthetic, y2k, oversized">
                </div>
            </x-modern.card>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bx bx-save me-2"></i> Publish Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>


<!-- Quick Add Modals -->
<!-- Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content quick-add-form" action="{{ route('categories.store') }}" method="POST" data-target-select="#category_id" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content quick-add-form" action="{{ route('brands.store') }}" method="POST" data-target-select="#brand_id" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Color Modal -->
<div class="modal fade" id="addColorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content quick-add-form" action="{{ route('colors.store') }}" method="POST" data-target-select="#selected_colors">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Color</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Size Modal -->
<div class="modal fade" id="addSizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content quick-add-form" action="{{ route('sizes.store') }}" method="POST" data-target-select="#selected_sizes">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Size</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-save">Save changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    // Colors data from PHP for the image selects
    const availableColors = @json($colors);
    
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

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

        // AJAX Subcategory Loading
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

        // --- Drag & Drop Image Uploader Logic ---
        const fileInput = document.getElementById('fileInput');
        const dropzone = document.getElementById('dropzoneArea');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const mainImageInput = document.getElementById('main_image_name');
        
        let dataTransfer = new DataTransfer(); // Holds actual files

        // Drag events
        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });
        
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if(!file.type.match('image.*')) return;
                
                // Add to dataTransfer
                dataTransfer.items.add(file);
                
                // Create preview element
                const reader = new FileReader();
                reader.onload = (e) => {
                    createPreviewElement(e.target.result, file.name);
                }
                reader.readAsDataURL(file);
            });
            // Update input
            fileInput.files = dataTransfer.files;
        }

        function createPreviewElement(src, filename) {
            // Generate color options
            let colorOptions = '<option value="">No Color</option>';
            $('#selected_colors option:selected').each(function() {
                colorOptions += `<option value="${$(this).val()}">${$(this).data('name')}</option>`;
            });

            // Set first image as main automatically if none exists
            if(mainImageInput.value === '') {
                mainImageInput.value = filename;
            }
            let isMain = (mainImageInput.value === filename);
            let checkedAttr = isMain ? 'checked' : '';

            const item = document.createElement('div');
            item.className = 'image-preview-item';
            item.dataset.filename = filename;
            
            item.innerHTML = `
                <button type="button" class="remove-image" onclick="removeImage('${filename}', this)" title="Remove Image">
                    <i class="bx bx-x"></i>
                </button>
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
            // Remove from UI
            btnElement.closest('.image-preview-item').remove();
            
            // Remove from DataTransfer
            const newDataTransfer = new DataTransfer();
            Array.from(dataTransfer.files).forEach(file => {
                if(file.name !== filename) newDataTransfer.items.add(file);
            });
            dataTransfer = newDataTransfer;
            fileInput.files = dataTransfer.files;

            // Handle main image reset if removed
            if(mainImageInput.value === filename) {
                mainImageInput.value = '';
                // Set to first available
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

        // Enable sorting for the image preview wrapper
        new Sortable(previewContainer, {
            animation: 150,
            ghostClass: 'bg-light'
        });

        $('#productForm').submit(function() {
            let order = [];
            $('.image-preview-item').each(function() {
                order.push($(this).data('filename'));
            });
            $('#image_sort_order').val(JSON.stringify(order));
        });
        
        // --- Variations Matrix Generator ---
        $('#generateVariationsBtn').click(function() {
            let colors = [];
            let sizes = [];
            
            $('#selected_colors option:selected').each(function() {
                colors.push({ id: $(this).val(), name: $(this).data('name') });
            });
            
            $('#selected_sizes option:selected').each(function() {
                sizes.push({ id: $(this).val(), name: $(this).data('name') });
            });
            
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
            
            let index = 0;
            
            // If only colors selected
            if(colors.length > 0 && sizes.length === 0) {
                colors.forEach(c => {
                    appendRow(index++, c.name, null, c.id, null, basePrice, baseSku + '-' + c.name);
                });
            }
            // If only sizes selected
            else if(sizes.length > 0 && colors.length === 0) {
                sizes.forEach(s => {
                    appendRow(index++, s.name, s.id, null, s.name, basePrice, baseSku + '-' + s.name);
                });
            }
            // Both selected (Cartesian Product)
            else {
                colors.forEach(c => {
                    sizes.forEach(s => {
                        let variantName = c.name + ' - ' + s.name;
                        let skuSuffix = c.name.substring(0,3).toUpperCase() + '-' + s.name;
                        appendRow(index++, variantName, s.id, c.id, variantName, basePrice, baseSku + '-' + skuSuffix);
                    });
                });
            }
        });

        function appendRow(index, variantName, sizeId, colorId, skuSuffix, price, sku) {
            let html = `
                <tr>
                    <td class="fw-bold">
                        ${variantName}
                        <input type="hidden" name="variations[${index}][size_id]" value="${sizeId || ''}">
                        <input type="hidden" name="variations[${index}][color_id]" value="${colorId || ''}">
                    </td>
                    <td><input type="text" class="form-control form-control-sm text-center" name="variations[${index}][sku]" value="${sku}" required></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm text-center" name="variations[${index}][price]" value="${price}" required></td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-icon rounded-circle remove-row-btn"><i class="bx bx-trash"></i></button>
                    </td>
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
