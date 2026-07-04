@extends('backend.layouts.master')

@section('title', 'Edit Page')

@section('content')

@can('edit-page')
<div class="row">
    <div class="col-xl-9 col-lg-10 mx-auto">
        <x-modern.card title="Edit Dynamic Page" icon="bx bx-edit">
            <x-slot name="actions">
                <x-modern.actions.button tag="a" href="{{ route('pages.index') }}" actionType="back" outline />
            </x-slot>

            <form action="{{ route('pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <x-modern.input label="Page Title" name="title" id="title" placeholder="e.g. About Us" :value="old('title', $page->title)" icon="bx bx-text" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-modern.input label="URL Slug" name="slug" id="slug" placeholder="e.g. about-us" :value="old('slug', $page->slug)" icon="bx bx-link" required />
                        <small class="text-muted mt-1 d-block">Be careful changing this if the page is already linked elsewhere.</small>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Page Content</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="12" placeholder="Write the content of your page here...">{{ old('content', $page->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="form-check form-switch form-switch-lg">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1" {{ old('status', $page->status) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 mt-1" for="status">Publish Page (Make it visible to users)</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <x-modern.actions.button tag="a" href="{{ route('pages.index') }}" actionType="back" label="Cancel" variant="light" icon="bx bx-x" />
                    <x-modern.actions.button type="submit" actionType="save" label="Update Page" icon="bx bx-check" />
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
    $(document).ready(function() {
        // Optional: Auto-generate slug from title (only if the user wants to change it)
        $('#title').on('input', function() {
            // Uncomment this if you want it to always sync even on edit
            /*
            var title = $(this).val();
            var slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
            */
        });
    });
</script>
@endpush
