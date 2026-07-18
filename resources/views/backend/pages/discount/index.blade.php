@extends('backend.layouts.master')

@section('title', 'Discounts')

@push('css')
<style>
    .discount-badge {
        font-size: 11px;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .level-badge {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .6px;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .session-dates {
        font-size: 12px;
        color: #8592a3;
    }
</style>
@endpush

@section('content')

@can('list-discount')

<x-modern.filter title="Filter Discounts" icon="bx bx-search-alt" :resetUrl="route('discounts.index')"
    :expanded="request()->anyFilled(['search','level','status','date_from','date_to'])">
    <div class="col-md-3">
        <x-modern.input label="Search Name" name="search" placeholder="Search discount name..." :value="request('search')" icon="bx bx-search" />
    </div>
    <div class="col-md-2">
        <label class="form-label">Level</label>
        <select name="level" class="form-select">
            <option value="">All Levels</option>
            <option value="category" {{ request('level') === 'category' ? 'selected' : '' }}>Category</option>
            <option value="product"  {{ request('level') === 'product'  ? 'selected' : '' }}>Product</option>
            <option value="variation"{{ request('level') === 'variation'? 'selected' : '' }}>Variation</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>🟢 Active</option>
            <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>🔵 Upcoming</option>
            <option value="expired"  {{ request('status') === 'expired'  ? 'selected' : '' }}>🔴 Expired</option>
            <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>⚫ Disabled</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Session From</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Session To</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
</x-modern.filter>

<x-modern.card title="Discount Rules" icon="bx bx-purchase-tag-alt">
    <x-slot name="actions">
        @can('create-discount')
        <x-modern.actions.button tag="a" href="{{ route('discounts.create') }}" actionType="add" label="Add Discount" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Discount Name', 'Level', 'Target', 'Discount %', 'Session Period', 'Status', 'Actions']">
        @forelse($discounts as $discount)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($discounts->currentPage() - 1) * $discounts->perPage() }}</td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $discount->name }}</span>
            </td>
            <td class="align-middle">
                @if($discount->level === 'category')
                    <span class="level-badge bg-purple-subtle text-purple border border-purple-subtle" style="background:#f3e8ff;color:#7c3aed;border-color:#c4b5fd!important;">Category</span>
                @elseif($discount->level === 'product')
                    <span class="level-badge bg-primary-subtle text-primary border border-primary-subtle">Product</span>
                @else
                    <span class="level-badge bg-warning-subtle text-warning border border-warning-subtle">Variation</span>
                @endif
            </td>
            <td class="align-middle">
                @if($discount->level === 'category')
                    <span class="small">{{ optional($discount->category)->name ?? '—' }}</span>
                @elseif($discount->level === 'product')
                    <span class="small">{{ optional($discount->product)->name ?? '—' }}</span>
                @else
                    <div class="small fw-bold">{{ optional($discount->variation)->sku ?? '—' }}</div>
                    <div class="small text-muted">{{ optional($discount->product)->name ?? '' }}</div>
                @endif
            </td>
            <td class="align-middle text-center">
                <span class="fw-bold fs-6 text-success">{{ number_format($discount->discount_percentage, 1) }}%</span>
                <div class="small text-muted">off</div>
            </td>
            <td class="align-middle">
                <div class="session-dates">
                    <i class="bx bx-calendar-check me-1 text-success"></i>{{ $discount->start_date->format('d M Y') }}
                </div>
                <div class="session-dates">
                    <i class="bx bx-calendar-x me-1 text-danger"></i>{{ $discount->end_date->format('d M Y') }}
                </div>
            </td>
            <td class="align-middle">
                @php $statusLabel = $discount->status_label; @endphp
                @if($statusLabel === 'active')
                    <span class="discount-badge bg-success-subtle text-success border border-success-subtle">🟢 Active</span>
                @elseif($statusLabel === 'upcoming')
                    <span class="discount-badge bg-info-subtle text-info border border-info-subtle">🔵 Upcoming</span>
                @elseif($statusLabel === 'expired')
                    <span class="discount-badge bg-danger-subtle text-danger border border-danger-subtle">🔴 Expired</span>
                @else
                    <span class="discount-badge bg-secondary-subtle text-secondary border border-secondary-subtle">⚫ Disabled</span>
                @endif
            </td>
            <td class="align-middle text-center">
                <div class="d-flex gap-2 justify-content-center">
                    @can('edit-discount')
                    <x-modern.actions.button tag="a" href="{{ route('discounts.edit', $discount->id) }}" actionType="edit" outline />
                    @endcan
                    @can('delete-discount')
                    <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="button" actionType="delete" outline class="delete-discount-btn" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-tag text-light" style="font-size:80px;"></i>
                </div>
                <h5 class="fw-bold">No Discount Rules Found</h5>
                <p class="text-muted mb-0">Create your first discount rule to apply savings across categories, products, or specific variations.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$discounts" />
</x-modern.card>

@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have permission to view the discount rules list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-discount-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Delete Discount?',
            text: 'This discount rule will be permanently removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush
