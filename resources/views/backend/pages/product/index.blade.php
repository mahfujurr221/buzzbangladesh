@extends('backend.layouts.master')

@section('title', 'Products')

@section('content')

@can('list-product')
<x-modern.filter title="Filter Products" icon="bx bx-search-alt" :resetUrl="route('products.index')"
    :expanded="request()->anyFilled(['search'])">
    <div class="col-md-6">
        <x-modern.input label="Search Keyword" name="search" placeholder="Search by name or SKU..." :value="request('search')"
            icon="bx bx-search" />
    </div>
</x-modern.filter>

<x-modern.card title="Product List" icon="bx bx-box">
    <x-slot name="actions">
        @can('create-product')
        <x-modern.actions.button tag="a" href="{{ route('products.create') }}" actionType="add" label="Add New" size="sm" />
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Image', 'Product Name', 'Category', 'Price', 'Status', 'Actions']">
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
            </td>
            <td class="align-middle">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius: 6px;">{{ $product->category->name }}</span>
                @if($product->subCategory)
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 ms-1" style="border-radius: 6px;">{{ $product->subCategory->name }}</span>
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

@else
<x-modern.card title="Access Restricted" icon="bx bx-lock-alt">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bx bx-shield-x text-danger opacity-25" style="font-size: 80px;"></i>
        </div>
        <h4 class="fw-bold">Unauthorized Access</h4>
        <p class="text-muted">You do not have the required permissions to view the product list.</p>
        <x-modern.actions.button tag="a" href="{{ route('dashboard') }}" label="Return to Dashboard" variant="light" icon="bx bx-home-alt" />
    </div>
</x-modern.card>
@endcan

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
