@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'error' => null,
    'multiple' => false,
    'allowSelectAll' => false,
    'containerClass' => 'mb-3'
])

@php
    $baseName = str_replace(['[', ']'], '', $name);
    $finalId = $id ?? $baseName . '_' . rand(100, 999);
    $selectName = $baseName . ($multiple ? '[]' : '');
@endphp

<div class="{{ $containerClass }}" wire:ignore>
    @if($label)
    <div class="d-flex justify-content-between align-items-center mb-1">
        <label for="{{ $finalId }}" class="form-label fw-bold text-dark mb-0" style="font-size: 0.85rem;">
            {{ $label }}
            @if($required) <span class="text-danger">*</span> @endif
        </label>
        @if($multiple && $allowSelectAll)
        <button type="button"
            class="btn btn-link py-0 px-0 font-size-12 text-decoration-none select-all-toggle fw-bold"
            data-target="{{ $finalId }}" data-state="none" style="color: #629D23;">
            <i class="bx bx-check-double me-1"></i>Select All
        </button>
        @endif
    </div>
    @endif

    <select name="{{ $selectName }}" id="{{ $finalId }}" @if($required) required @endif
        @if($multiple) multiple @endif {{ $attributes->merge(['class' => 'form-control select2 modern-select2 ' .
        ($errors->has($baseName) || $errors->has($name) || $error ? 'is-invalid' : '')]) }}
        style="width: 100%;"
        data-placeholder="{{ $placeholder }}"
        >
        @if(!$multiple)
        <option value=""></option>
        @endif

        @foreach($options as $value => $labelOption)
        @php
        $oldVal = old($baseName, $selected);
        $isSelected = is_array($oldVal)
            ? in_array($value, $oldVal)
            : $oldVal == $value;
        @endphp
        <option value="{{ $value }}" @if($isSelected) selected @endif>
            {{ $labelOption }}
        </option>
        @endforeach

        {{ $slot }}
    </select>

    @if($errors->has($name) || $error)
    <div class="invalid-feedback d-block ps-2">
        {{ $error ?? $errors->first($name) }}
    </div>
    @endif
</div>

@once
@push('css')
<style>
    /* Modern Select2 Styling */
    .select2-container--bootstrap-5 {
        display: block;
        width: 100% !important;
    }

    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 12px !important;
        border-color: #e2e8f0 !important;
        min-height: 42px !important;
        padding: 0.35rem 0.5rem !important;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        background-color: #ffffff !important;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #629D23 !important;
        box-shadow: 0 0 0 3px rgba(98, 157, 35, 0.1) !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-radius: 12px !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        z-index: 1060 !important;
        overflow: hidden !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #629D23 !important;
        color: #ffffff !important;
    }

    .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
        background-color: #f0fdf4 !important;
        color: #629D23 !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #629D23 !important;
        color: #ffffff !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected=true] {
        background-color: #629D23 !important;
        color: #ffffff !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #f0fdf4 !important;
        color: #629D23 !important;
        border: 1px solid rgba(98, 157, 35, 0.2) !important;
        border-radius: 8px !important;
        padding: 2px 10px !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #629D23 !important;
        margin-right: 5px !important;
        border: 0 !important;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent !important;
        color: #dc2626 !important;
    }
</style>
@endpush
@endonce

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            const $select = $('#{{ $finalId }}');
            
            // Initialize Select2
            $select.select2({
                theme: 'bootstrap-5',
                placeholder: $select.data('placeholder'),
                allowClear: true,
                dropdownParent: $select.parent()
            });

            @if($multiple && $allowSelectAll)
            // Handle Select All Toggle
            const $toggleBtn = $('.select-all-toggle[data-target="{{ $finalId }}"]');
            
            if ($toggleBtn.length) {
                $toggleBtn.on('click', function() {
                    const currentState = $(this).data('state');
                    
                    if (currentState === 'all') {
                        // Unselect All
                        $select.val(null).trigger('change');
                        $(this).html('<i class="bx bx-check-double me-1"></i>Select All').data('state', 'none');
                    } else {
                        // Select All
                        const allValues = $select.find('option').map(function() {
                            return $(this).val();
                        }).get().filter(v => v !== "");
                        
                        $select.val(allValues).trigger('change');
                        $(this).html('<i class="bx bx-x me-1"></i>Unselect All').data('state', 'all');
                    }
                });

                // Sync button state if user manually changes selection or it initializes
                const syncToggleState = () => {
                    const totalOptions = $select.find('option').length - ($select.find('option[value=""]').length);
                    const selectedOptions = $select.val() ? $select.val().length : 0;
                    
                    if (selectedOptions === totalOptions && totalOptions > 0) {
                        $toggleBtn.html('<i class="bx bx-x me-1"></i>Unselect All').data('state', 'all');
                    } else {
                        $toggleBtn.html('<i class="bx bx-check-double me-1"></i>Select All').data('state', 'none');
                    }
                };

                $select.on('change', syncToggleState);
                syncToggleState();
            }
            @endif
        }
    });
</script>
@endpush