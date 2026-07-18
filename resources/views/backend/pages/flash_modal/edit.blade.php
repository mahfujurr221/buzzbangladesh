@extends('backend.layouts.master')
@section('title', 'Edit Flash Modal')

@section('content')

@can('edit-flash-modal')
<form action="{{ route('flash-modals.update', $flashModal->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12 col-lg-8">
            <x-modern.card title="Modal Details" class="mb-4" icon="bx bx-detail">
                <div class="mb-3">
                    <x-modern.input label="Title" name="title" placeholder="e.g. Flash Sale Live Now!" :value="old('title', $flashModal->title)" icon="bx bx-text" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Banner Image</label>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep the current image. Recommended size: 600x600 or 800x600 pixels (JPEG, PNG, WEBP).</small>
                    <div class="mt-3">
                        <label class="form-label text-muted small">Current/Preview Image:</label>
                        <br>
                        <img src="{{ asset($flashModal->image) }}" id="imagePreview" alt="Current Banner" class="img-fluid rounded border" style="max-height: 200px;">
                    </div>
                </div>
                <div class="mb-3">
                    <x-modern.input label="Destination Link" name="link" placeholder="e.g. https://example.com/sale" :value="old('link', $flashModal->link)" icon="bx bx-link" />
                    <small class="text-muted">Optional. Where the user is taken if they click the banner.</small>
                </div>
            </x-modern.card>
        </div>

        <div class="col-12 col-lg-4">
            <x-modern.card title="Display Settings" class="mb-4" icon="bx bx-slider">
                <div class="mb-3">
                    <label class="form-label">Popup Delay (Seconds) <span class="text-danger">*</span></label>
                    <input type="number" name="delay_seconds" class="form-control" value="{{ old('delay_seconds', $flashModal->delay_seconds) }}" min="0" required>
                    <small class="text-muted">How many seconds to wait before showing the popup.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $flashModal->start_date->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Date & Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $flashModal->end_date->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="form-check form-switch mb-3 mt-4">
                    <input class="form-check-input" type="checkbox" name="active_status" value="1" id="active_status" {{ $flashModal->active_status ? 'checked' : '' }}>
                    <label class="form-check-label" for="active_status">Active Status</label>
                </div>
            </x-modern.card>

            <div class="d-flex justify-content-end gap-2">
                <x-modern.actions.button tag="a" href="{{ route('flash-modals.index') }}" actionType="cancel" outline />
                <x-modern.actions.button type="submit" label="Update Modal" icon="bx bx-save" variant="primary" />
            </div>
        </div>
    </div>
</form>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have permission to edit flash modals.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection

@push('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewImage = document.getElementById('imagePreview');
        const originalImage = "{{ asset($flashModal->image) }}";
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = originalImage;
        }
    });
</script>
@endpush
