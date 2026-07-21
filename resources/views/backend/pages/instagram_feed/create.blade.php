@extends('backend.layouts.master')

@section('title', 'Add Instagram Feed')

@section('content')
<x-modern.card title="Add New Instagram Feed" icon="bx bx-plus-circle">
    <x-slot name="actions">
        <x-modern.actions.button tag="a" href="{{ route('instagram-feeds.index') }}" actionType="back" label="Back to List" size="sm" />
    </x-slot>

    <form action="{{ route('instagram-feeds.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <x-modern.input label="Instagram Link" name="link" placeholder="Enter instagram post link (optional)..." :value="old('link')" icon="bx bx-link" />
            </div>

            <div class="col-md-12 mb-4">
                <label class="form-label fw-bold">Feed Image <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control" required accept="image/*" onchange="document.getElementById('preview_image').src = window.URL.createObjectURL(this.files[0])">
                <div class="mt-2">
                    <img id="preview_image" src="{{ asset('backend/images/placeholder.png') }}" alt="Preview" class="img-thumbnail" style="height: 150px; width: 150px; object-fit: cover;">
                </div>
                @error('image')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" checked>
                    <label class="form-check-label fw-bold" for="status">Active Status</label>
                </div>
            </div>

            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Save Feed
                </button>
            </div>
        </div>
    </form>
</x-modern.card>
@endsection
