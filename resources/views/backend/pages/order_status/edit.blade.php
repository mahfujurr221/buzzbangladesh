@extends('backend.layouts.master')

@section('title', 'Edit Order Status')

@section('content')
<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <x-modern.card title="Edit Order Status: {{ $orderStatus->name }}" icon="bx bx-edit-alt">
            <x-slot name="actions">
                <x-modern.actions.button tag="a" href="{{ route('order-statuses.index') }}" actionType="back" outline />
            </x-slot>

            <form action="{{ route('order-statuses.update', $orderStatus->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <x-modern.input label="Status Name" name="name" value="{{ $orderStatus->name }}" placeholder="e.g. Processing, Shipped, Delivered" required icon="bx bx-tag" />
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">Status Color (Hex) <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" class="form-control form-control-color border-0 p-1" style="width: 50px; height: 45px; border-radius: 10px; cursor: pointer;" id="colorPicker" value="{{ $orderStatus->color_code }}" title="Choose your color">
                        <div class="flex-grow-1">
                            <x-modern.input name="color_code" id="colorCodeInput" value="{{ $orderStatus->color_code }}" placeholder="#000000" required containerClass="mb-0" />
                        </div>
                    </div>
                    <div class="form-text text-muted font-size-12 mt-1 ps-2">Select a color to represent this status visually.</div>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch modern-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_default" name="is_default" value="1" {{ $orderStatus->is_default ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold ms-2" for="is_default">Set as Default Status for New Orders</label>
                    </div>
                    <div class="form-text text-muted font-size-12 mt-1 ms-4 ps-3">If enabled, any existing default status will be replaced.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-modern.actions.button type="submit" actionType="update" size="lg" />
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@endsection

@push('css')
<style>
    .modern-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .modern-switch .form-check-input:checked {
        background-color: #629D23;
        border-color: #629D23;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const picker = document.getElementById('colorPicker');
        const input = document.getElementById('colorCodeInput');
        
        picker.addEventListener('input', function() {
            input.value = this.value.toUpperCase();
        });
        
        input.addEventListener('input', function() {
            if(/^#[0-9A-F]{6}$/i.test(this.value)) {
                picker.value = this.value;
            }
        });
    });
</script>
@endpush
