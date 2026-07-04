@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'icon' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'containerClass' => 'mb-3'
])

@php
    $finalId = $id ?? $name;
@endphp

<div class="{{ $containerClass }}">
    @if($label)
    <label for="{{ $finalId }}" class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">
        {{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    @endif

    <div class="input-group modern-input-group @if($icon) has-icon @endif">
        @if($icon)
        <span class="input-group-text border-end-0 bg-transparent text-muted px-3"
            style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
            <i class="{{ $icon }} fs-5"></i>
        </span>
        @endif

        @if($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $finalId }}" placeholder="{{ $placeholder }}" @if($required) required @endif
            {{ $attributes->merge(['class' => 'form-control modern-input ' . ($name && $errors->has($name) || $error ? 'is-invalid' : '')]) }}
            style="border-radius: {{ $icon ? '0 12px 12px 0' : '12px' }}; border-color: #e2e8f0; padding: 0.7rem 1rem;"
            >{{ $name ? old($name, $value) : $value }}</textarea>
        @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $finalId }}" value="{{ $name ? old($name, $value) : $value }}"
            placeholder="{{ $placeholder }}" @if($required) required @endif {{ $attributes->merge(['class' =>
        'form-control modern-input ' . ($name && $errors->has($name) || $error ? 'is-invalid' : '')]) }}
        style="border-radius: {{ $icon ? '0 12px 12px 0' : '12px' }}; border-color: #e2e8f0; padding: 0.7rem 1rem;"
        >
        @endif

        @if(($name && $errors->has($name)) || $error)
        <div class="invalid-feedback ps-2">
            {{ $error ?? ($name ? $errors->first($name) : '') }}
        </div>
        @endif
    </div>

    @if($help)
    <div class="form-text text-muted font-size-12 mt-1 ps-2">{{ $help }}</div>
    @endif
</div>

<style>
    .modern-input {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        background-color: #ffffff;
    }
    .modern-input:focus {
        border-color: #629D23 !important;
        box-shadow: 0 0 0 3px rgba(98, 157, 35, 0.1) !important;
        background-color: #fff;
    }
    .modern-input::placeholder {
        color: #94a3b8;
        font-size: 0.85rem;
    }
    .modern-input-group.has-icon .input-group-text {
        transition: all 0.3s ease;
    }
    .modern-input-group.has-icon:focus-within .input-group-text {
        border-color: #629D23 !important;
        color: #629D23 !important;
    }
</style>