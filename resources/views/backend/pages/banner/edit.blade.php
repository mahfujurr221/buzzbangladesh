@extends('backend.layouts.master')

@section('title', 'Edit Banner')

@section('content')

@can('edit-banner')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <x-modern.card title="Edit Banner" icon="bx bx-edit">
            <x-slot name="actions">
                <x-modern.actions.button tag="a" href="{{ route('banners.index') }}" actionType="back" outline />
            </x-slot>

            <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Banner Title" name="title" placeholder="Enter banner title" :value="old('title', $banner->title)" icon="bx bx-text" />
                        <small class="text-muted mt-1 d-block">Optional: A catchy heading for the banner.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Subtitle" name="subtitle" placeholder="Enter banner subtitle" :value="old('subtitle', $banner->subtitle)" icon="bx bx-text" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Button Text" name="button_text" placeholder="e.g. Shop Now" :value="old('button_text', $banner->button_text)" icon="bx bx-pointer" />
                        <small class="text-muted mt-1 d-block">Optional: Text displayed on the banner button.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Button Link (URL)" name="button_link" placeholder="https://example.com/promo" :value="old('button_link', $banner->button_link)" icon="bx bx-link" type="url" />
                        <small class="text-muted mt-1 d-block">Optional: Where should this button take the user?</small>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Banner Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(this, 'imagePreview')">
                        <small class="text-muted mt-1 d-block">Leave blank if you want to keep the existing image.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="imagePreview" src="{{ asset($banner->image) }}" alt="Image Preview" class="img-thumbnail" style="max-height: 200px; width: auto; object-fit: cover;">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="form-check form-switch form-switch-lg">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 mt-1" for="status">Active (Banner is visible)</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <x-modern.actions.button tag="a" href="{{ route('banners.index') }}" actionType="back" label="Cancel" variant="light" icon="bx bx-x" />
                    <x-modern.actions.button type="submit" actionType="save" label="Update Banner" icon="bx bx-check" />
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to perform this action.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
