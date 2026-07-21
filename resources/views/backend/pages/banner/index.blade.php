@extends('backend.layouts.master')

@section('title', 'Banners')

@section('content')

@can('list-banner')
<x-modern.filter title="Filter Banners" icon="bx bx-search-alt" :resetUrl="route('banners.index')"
    :expanded="request()->anyFilled(['search'])" class="mb-4">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by title or subtitle..." :value="request('search')"
            icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Banner List" icon="bx bx-image">
    <x-slot name="actions">
        @can('create-banner')
        <x-modern.actions.button tag="a" href="{{ route('banners.create') }}" actionType="add" label="Add New Banner" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Image', 'Title', 'Link', 'Status', 'Actions']">
        @forelse ($banners as $key => $banner)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($banners->currentPage() - 1) * $banners->perPage() }}</td>
            <td class="align-middle">
                <img src="{{ asset($banner->image) }}" alt="Banner" class="rounded img-thumbnail" style="height: 60px; width: 140px; object-fit: cover;">
            </td>
            <td class="align-middle fw-bold">
                {{ $banner->title ?? 'N/A' }}
                @if($banner->subtitle)
                    <div class="text-muted small fw-normal">{{ $banner->subtitle }}</div>
                @endif
            </td>
            <td class="align-middle">
                @if($banner->button_link)
                    <a href="{{ $banner->button_link }}" target="_blank" class="text-primary"><i class="bx bx-link-external"></i> {{ $banner->button_text ?: 'Link' }}</a>
                @else
                    <span class="text-muted">None</span>
                @endif
            </td>
            <td class="align-middle">
                <form action="{{ route('banners.toggle-status') }}" method="POST" class="d-inline status-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $banner->id }}">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()"
                            {{ $banner->status ? 'checked' : '' }}
                            {{ !auth()->user()->can('edit-banner') ? 'disabled' : '' }}>
                    </div>
                </form>
            </td>
            <td class="align-middle">
                <div class="d-flex justify-content-center gap-2">
                    @can('edit-banner')
                    <x-modern.actions.button tag="a" href="{{ route('banners.edit', $banner->id) }}" actionType="edit" outline />
                    @endcan
                    
                    @can('delete-banner')
                    <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="submit" actionType="delete" outline 
                            onclick="return confirm('Are you sure you want to delete this banner?')" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="bx bx-image text-secondary opacity-50" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-bold text-secondary">No Banners Found</h5>
                <p class="text-muted">You haven't added any banners yet.</p>
                @can('create-banner')
                <a href="{{ route('banners.create') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-plus me-1"></i> Add Your First Banner
                </a>
                @endcan
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <div class="mt-4">
        {{ $banners->withQueryString()->links() }}
    </div>

</x-modern.card>
@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view this module.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection
