@props([
    'label' => null,
    'icon' => null,
    'variant' => 'primary', // primary, success, danger, warning, info, light, dark, secondary
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'tag' => 'button', // button or a
    'loading' => false,
    'disabled' => false,
    'outline' => false,
    'actionType' => null // save, update, edit, delete, add, back, submit, cancel
])

@php
    $actionPresets = [
        'save'   => ['label' => 'Save Changes', 'icon' => 'bx bx-save',           'variant' => 'primary'],
        'update' => ['label' => 'Update Data',  'icon' => 'bx bx-rotate-right',  'variant' => 'primary'],
        'edit'   => ['label' => 'Edit',         'icon' => 'bx bx-edit-alt',      'variant' => 'info',    'size' => 'sm'],
        'delete' => ['label' => 'Delete',       'icon' => 'bx bx-trash',         'variant' => 'danger',  'size' => 'sm'],
        'add'    => ['label' => 'Add New',      'icon' => 'bx bx-plus',          'variant' => 'primary'],
        'back'   => ['label' => 'Go Back',      'icon' => 'bx bx-arrow-back',    'variant' => 'secondary'],
        'submit' => ['label' => 'Submit',       'icon' => 'bx bx-check-circle',  'variant' => 'primary'],
        'cancel' => ['label' => 'Cancel',       'icon' => 'bx bx-x',             'variant' => 'danger',  'size' => 'sm'],
    ];

    $finalPreset = ($actionType && isset($actionPresets[$actionType])) ? $actionPresets[$actionType] : [];
    
    $finalLabel = $label ?? ($finalPreset['label'] ?? '');
    $finalIcon  = $icon  ?? ($finalPreset['icon']  ?? '');
    $finalSize  = $size === 'md' && isset($finalPreset['size']) ? $finalPreset['size'] : $size;
    $finalVariant = $variant === 'primary' && isset($finalPreset['variant']) ? $finalPreset['variant'] : $variant;

    $classes = "btn modern-btn";
    $classes .= $outline ? " btn-outline-{$finalVariant}" : " btn-{$finalVariant}";
    $classes .= " btn-{$finalSize}";
    if($loading) $classes .= " loading";
@endphp

<{{ $tag }} 
    @if($tag === 'button') type="{{ $type }}" @endif 
    {{ $attributes->merge(['class' => $classes]) }}
    @if(($disabled || $loading) && $tag === 'button') disabled @endif
>
    @if($loading)
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @if($finalLabel) {{ $finalLabel }} @else Processing... @endif
    @else
        @if($finalIcon)
            <i class="{{ $finalIcon }} @if($finalLabel) me-2 @endif fs-5"></i>
        @endif
        @if($finalLabel)
            <span class="modern-btn-label">{{ $finalLabel }}</span>
        @endif
    @endif
    {{ $slot }}
</{{ $tag }}>

@once
@push('css')
<style>
    .modern-btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.6rem 1.4rem;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        font-size: 0.85rem;
        letter-spacing: 0.3px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .modern-btn.btn-sm {
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
    }

    .modern-btn.btn-lg {
        padding: 0.8rem 2rem;
        font-size: 1rem;
    }

    /* Hover States with subtle lift and shadow increase */
    .modern-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        color: inherit;
    }

    .modern-btn:not(.btn-outline-light):hover:not(:disabled) {
        /* filter: brightness(1.05); */
    }

    .modern-btn:not(.btn-outline-light):hover {
          color: white;
    }

    .modern-btn:active:not(:disabled) {
        transform: translateY(0);
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Primary Brand Button Customization */
    .btn-primary.modern-btn {
        background: #629D23;
        border-color: #629D23;
        color: #fff;
    }

    .btn-primary.modern-btn:hover {
        background: #73B829 !important;
        border-color: #73B829 !important;
    }

    .btn-secondary.modern-btn {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #475569;
        box-shadow: none;
    }

    .btn-secondary.modern-btn:hover {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
        color: #1e293b;
    }

    /* Icon adjustments */
    .modern-btn i {
        transition: transform 0.2s ease;
    }

    .modern-btn:hover i {
        /* transform: scale(1.1); */
    }
</style>
@endpush
@endonce