@extends('backend.layouts.master')
@section('title', 'Add Flash Modal')

@section('content')

@can('create-flash-modal')
<form action="{{ route('flash-modals.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12 col-lg-8">
            <x-modern.card title="Modal Details" class="mb-4" icon="bx bx-detail">
                <div class="mb-3">
                    <x-modern.input label="Title" name="title" placeholder="e.g. Flash Sale Live Now!" :value="old('title')" icon="bx bx-text" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Banner Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" required>
                    <small class="text-muted">Recommended size: 600x600 or 800x600 pixels (JPEG, PNG, WEBP).</small>
                    <div class="mt-3" id="imagePreviewContainer">
                        <label class="form-label text-muted small">Image Preview:</label>
                        <br>
                        <img src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22600%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20600%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f8f9fa%22%2F%3E%3Ctext%20x%3D%22300%22%20y%3D%22100%22%20fill%3D%22%236c757d%22%20font-family%3D%22sans-serif%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%20Selected%3C%2Ftext%3E%3C%2Fsvg%3E" 
                             id="imagePreview" alt="Preview" class="img-fluid rounded border" style="height: 200px; width: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="mb-3">
                    <x-modern.input label="Destination Link" name="link" placeholder="e.g. https://example.com/sale" :value="old('link')" icon="bx bx-link" />
                    <small class="text-muted">Optional. Where the user is taken if they click the banner.</small>
                </div>
            </x-modern.card>
        </div>

        <div class="col-12 col-lg-4">
            <x-modern.card title="Display Settings" class="mb-4" icon="bx bx-slider">
                <div class="mb-3">
                    <label class="form-label">Popup Delay (Seconds) <span class="text-danger">*</span></label>
                    <input type="number" name="delay_seconds" class="form-control" value="{{ old('delay_seconds', 3) }}" min="0" required>
                    <small class="text-muted">How many seconds to wait before showing the popup.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Date & Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
                <div class="form-check form-switch mb-3 mt-4">
                    <input class="form-check-input" type="checkbox" name="active_status" value="1" id="active_status" checked>
                    <label class="form-check-label" for="active_status">Active Status</label>
                </div>
            </x-modern.card>

            <div class="d-flex justify-content-end gap-2">
                <x-modern.actions.button tag="a" href="{{ route('flash-modals.index') }}" actionType="cancel" outline />
                <x-modern.actions.button type="submit" actionType="save" />
            </div>
        </div>
    </div>
</form>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have permission to create flash modals.</p>
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
        const defaultPlaceholder = "data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22600%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20600%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f8f9fa%22%2F%3E%3Ctext%20x%3D%22300%22%20y%3D%22100%22%20fill%3D%22%236c757d%22%20font-family%3D%22sans-serif%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%20Selected%3C%2Ftext%3E%3C%2Fsvg%3E";
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = defaultPlaceholder;
        }
    });
</script>
@endpush
