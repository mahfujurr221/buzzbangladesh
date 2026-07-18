@extends('backend.layouts.master')

@section('title', 'Products')

@push('css')
<style>
    .product-label-badge {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .4px;
        padding: 2px 7px;
        border-radius: 20px;
        display: inline-block;
        margin-right: 3px;
        margin-bottom: 2px;
    }
</style>
@endpush

@section('content')

<x-modern.filter title="Filter Products" icon="bx bx-search-alt" :resetUrl="route('products.index')"
    :expanded="request()->anyFilled(['search','season_id','label','entry_date_from','entry_date_to'])">
    <div class="col-md-3">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by name or SKU..." :value="request('search')"
            icon="bx bx-search" />
    </div>
    <div class="col-md-2">
        <label class="form-label">Season</label>
        <select name="season_id" class="form-select">
            <option value="">All Seasons</option>
            @foreach($seasons as $season)
                <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>{{ $season->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Label</label>
        <select name="label" class="form-select">
            <option value="">All Labels</option>
            <option value="is_new_arrival" {{ request('label') === 'is_new_arrival' ? 'selected' : '' }}>New Arrival</option>
            <option value="is_featured"    {{ request('label') === 'is_featured'    ? 'selected' : '' }}>Featured</option>
            <option value="is_best_seller" {{ request('label') === 'is_best_seller' ? 'selected' : '' }}>Best Seller</option>
            <option value="is_on_sale"     {{ request('label') === 'is_on_sale'     ? 'selected' : '' }}>On Sale</option>
            <option value="is_trending"    {{ request('label') === 'is_trending'    ? 'selected' : '' }}>Trending</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Entry Date From</label>
        <input type="date" name="entry_date_from" class="form-control" value="{{ request('entry_date_from') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Entry Date To</label>
        <input type="date" name="entry_date_to" class="form-control" value="{{ request('entry_date_to') }}">
    </div>
</x-modern.filter>

<x-modern.card title="Product List" icon="bx bx-box">
    <x-slot name="actions">
        @can('create-product')
        <x-modern.actions.button tag="a" href="{{ route('products.create') }}" actionType="add" label="Add New" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Image', 'Product Name', 'Category / Season', 'Price', 'Status', 'Actions']">
        @forelse ($products as $key => $product)
        <tr>
            <td class="align-middle text-center">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
            <td class="align-middle">
                @if($product->images->count() > 0)
                    <img src="{{ asset($product->images->first()->image_path) }}" alt="{{ $product->name }}" class="rounded img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                        <i class="bx bx-image"></i>
                    </div>
                @endif
            </td>
            <td class="align-middle">
                <span class="fw-bold text-dark">{{ $product->name }}</span>
                @if($product->brand)
                    <div class="small text-muted">{{ $product->brand->name }}</div>
                @endif
                {{-- Label Badges --}}
                <div class="mt-1">
                    @if($product->is_new_arrival)
                        <span class="product-label-badge bg-info-subtle text-info border border-info-subtle">New Arrival</span>
                    @endif
                    @if($product->is_featured)
                        <span class="product-label-badge bg-warning-subtle text-warning border border-warning-subtle">Featured</span>
                    @endif
                    @if($product->is_best_seller)
                        <span class="product-label-badge bg-success-subtle text-success border border-success-subtle">Best Seller</span>
                    @endif
                    @if($product->is_on_sale)
                        <span class="product-label-badge bg-danger-subtle text-danger border border-danger-subtle">On Sale</span>
                    @endif
                    @if($product->is_trending)
                        <span class="product-label-badge" style="background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">Trending</span>
                    @endif
                    @if($product->entry_date)
                        <div class="small text-muted mt-1">
                            <i class="bx bx-calendar-check me-1"></i>Entered: {{ $product->entry_date->format('d M Y') }}
                        </div>
                    @endif
                </div>
            </td>
            <td class="align-middle">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius: 6px;">{{ $product->category->name }}</span>
                @if($product->subCategory)
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 ms-1" style="border-radius: 6px;">{{ $product->subCategory->name }}</span>
                @endif
                @if($product->season)
                    <div class="mt-1">
                        <span class="badge px-2 py-1" style="background:#fdf4ff;color:#9333ea;border:1px solid #e9d5ff;border-radius:20px;font-size:10px;">
                            {{ $product->season->name }}
                        </span>
                    </div>
                @endif
            </td>
            <td class="align-middle">
                <div class="fw-bold text-primary">৳{{ number_format($product->sale_price, 2) }}</div>
                @if($product->purchase_price)
                    <div class="small text-muted text-decoration-line-through">৳{{ number_format($product->purchase_price, 2) }}</div>
                @endif
            </td>
            <td class="align-middle">
                @if($product->active_status)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1" style="border-radius: 20px;">
                        <i class="bx bxs-circle font-size-8 me-1"></i>Active
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1" style="border-radius: 20px;">
                        <i class="bx bxs-circle font-size-8 me-1"></i>Inactive
                    </span>
                @endif
            </td>
            <td class="align-middle text-center">
                <div class="d-flex gap-2 justify-content-center">
                    @can('edit-product')
                    <x-modern.actions.button tag="a" href="{{ route('products.edit', $product->id) }}" actionType="edit" outline />
                    @endcan

                    @can('delete-product')
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-modern.actions.button type="button" actionType="delete" outline class="delete-btn" />
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-box text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Products Found</h5>
                <p class="text-muted mb-0">Try adjusting your filters or create a new product.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <x-modern.pagination :collection="$products" />
</x-modern.card>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the product and its variations!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
