@extends('backend.layouts.master')

@section('title', 'Instagram Feeds')

@section('content')

@can('list-instagram')
<x-modern.card title="Instagram Feed List" icon="bx bx-camera">
    <x-slot name="actions">
        @can('create-instagram')
        <x-modern.actions.button tag="a" href="{{ route('instagram-feeds.create') }}" actionType="add" label="Add New Instagram Feed" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Image', 'Link', 'Status', 'Actions']">
        @forelse ($feeds as $key => $feed)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($feeds->currentPage() - 1) * $feeds->perPage() }}</td>
            <td class="align-middle">
                <img src="{{ asset($feed->image) }}" alt="Instagram" class="rounded img-thumbnail" style="height: 60px; width: 60px; object-fit: cover;">
            </td>
            <td class="align-middle">
                @if($feed->link)
                    <a href="{{ $feed->link }}" target="_blank" class="text-primary"><i class="bx bx-link-external"></i> View Link</a>
                @else
                    <span class="text-muted">No Link</span>
                @endif
            </td>
            <td class="align-middle">
                <form action="{{ route('instagram-feeds.toggle-status') }}" method="POST" class="d-inline status-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $feed->id }}">
                    <input type="hidden" name="status" value="{{ $feed->status ? 0 : 1 }}">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()"
                            {{ $feed->status ? 'checked' : '' }}
                            {{ !auth()->user()->can('edit-instagram') ? 'disabled' : '' }}>
                    </div>
                </form>
            </td>
            <td class="align-middle">
                <div class="d-flex justify-content-center gap-2">
                    @can('edit-instagram')
                    <x-modern.actions.button tag="a" href="{{ route('instagram-feeds.edit', $feed->id) }}" actionType="edit" outline />
                    @endcan
                    
                    @can('delete-instagram')
                    <form action="{{ route('instagram-feeds.destroy', $feed->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="submit" actionType="delete" outline 
                            onclick="return confirm('Are you sure you want to delete this instagram feed?')" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="bx bx-camera text-secondary opacity-50" style="font-size: 48px;"></i>
                </div>
                <h5 class="fw-bold text-secondary">No Instagram Feeds Found</h5>
                <p class="text-muted">You haven't added any instagram feeds yet.</p>
                @can('create-instagram')
                <a href="{{ route('instagram-feeds.create') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-plus me-1"></i> Add Your First Feed
                </a>
                @endcan
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <div class="mt-4">
        {{ $feeds->withQueryString()->links() }}
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
