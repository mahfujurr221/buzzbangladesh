@extends('backend.layouts.master')

@section('title', 'Edit Instagram Feed')

@section('content')
<x-modern.card title="Edit Instagram Feed" icon="bx bx-edit">
    <x-slot name="actions">
        <x-modern.actions.button tag="a" href="{{ route('instagram-feeds.index') }}" actionType="back" label="Back to List" size="sm" />
    </x-slot>

    <form action="{{ route('instagram-feeds.update', $instagramFeed->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-modern.input label="Instagram Link" name="link" placeholder="Enter instagram post link (optional)..." :value="old('link', $instagramFeed->link)" icon="bx bx-link" />
            </div>

            <div class="col-md-12 mb-4">
                <label class="form-label fw-bold">Feed Image <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="document.getElementById('preview_image').src = window.URL.createObjectURL(this.files[0])">
                <small class="text-muted d-block mt-1">Leave empty if you don't want to change the image.</small>
                <div class="mt-2">
                    <img id="preview_image" src="{{ asset($instagramFeed->image) }}" alt="Preview" class="img-thumbnail" style="height: 150px; width: 150px; object-fit: cover;">
                </div>
                @error('image')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ $instagramFeed->status ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="status">Active Status</label>
                </div>
            </div>

            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Update Feed
                </button>
            </div>
        </div>
    </form>
</x-modern.card>
@endsection
