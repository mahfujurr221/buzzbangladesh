@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'required' => false,
    'error' => null,
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

    <select name="{{ $name }}" id="{{ $finalId }}" @if($required) required @endif {{ $attributes->merge(['class' =>
        'form-select modern-select ' . ($errors->has($name) || $error ? 'is-invalid' : '')]) }}
        style="border-radius: 12px; border-color: #e2e8f0; padding: 0.7rem 1rem;"
        >
        @if($placeholder)
        <option value="" disabled @if(is_null(old($name, $selected))) selected @endif>{{ $placeholder }}</option>
        @endif

        @foreach($options as $value => $labelOption)
        <option value="{{ $value }}" @selected($selected == $value)>
            {{ $labelOption }}
        </option>
        @endforeach

        {{ $slot }}
    </select>

    @if($errors->has($name) || $error)
    <div class="invalid-feedback ps-2">
        {{ $error ?? $errors->first($name) }}
    </div>
    @endif
</div>

<style>
    .modern-select {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        background-color: #ffffff;
    }
    .modern-select:focus {
        border-color: #629D23 !important;
        box-shadow: 0 0 0 3px rgba(98, 157, 35, 0.1) !important;
        background-color: #fff;
    }
</style>