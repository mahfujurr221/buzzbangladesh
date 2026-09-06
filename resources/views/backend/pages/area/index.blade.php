@extends('backend.layouts.master')

@section('title', 'Areas Management')

@section('content')
    <div class="row g-4">
        {{-- 1. Area Form (Create/Edit) --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        {{ isset($area) ? 'Edit Area' : 'New Area' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ isset($area) ? route('areas.update', $area->id) : route('areas.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($area))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Area Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $area->name ?? '') }}"
                                placeholder="e.g. Inside Dhaka" required>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Delivery Charge</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="delivery_charge" class="form-control"
                                    value="{{ old('delivery_charge', $area->delivery_charge ?? '') }}"
                                    placeholder="e.g. 60" required>
                            </div>
                            @error('delivery_charge')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ old('status', $area->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $area->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold py-2">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($area) ? 'Update Area' : 'Save Area' }}
                            </button>
                            @if (isset($area))
                                <a href="{{ route('areas.index') }}" class="btn btn-light mt-2 fw-bold">Cancel
                                    Edit</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. Area List --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Manage Areas</h5>
                    <span class="badge bg-primary rounded-pill">{{ $area_list->total() }} Areas</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Area Name</th>
                                    <th>Delivery Charge</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($area_list as $key => $item)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $area_list->firstItem() + $key }}</td>
                                        <td class="fw-bold">{{ $item->name }}</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                ৳ {{ number_format($item->delivery_charge, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                @can('edit-area')
                                                <a href="{{ route('areas.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-warning border-0" title="Edit">
                                                    <i class="bx bx-edit fs-5"></i>
                                                </a>
                                                @endcan
                                                @can('delete-area')
                                                <form action="{{ route('areas.destroy', $item->id) }}"
                                                    method="POST" onsubmit="return confirm('Delete this area?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                        title="Delete" {{ $item->default ? 'disabled' : '' }}>
                                                        <i class="bx bx-trash fs-5"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center p-5 text-muted">
                                            <i class="bx bx-map-pin h1 d-block mb-3"></i>
                                            No areas found yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination bar -->
                <div class="px-4 py-3 border-top">
                    {{ $area_list->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
