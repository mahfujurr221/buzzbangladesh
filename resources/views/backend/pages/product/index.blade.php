@extends('backend.layouts.master')

@section('title', 'Products')

@section('content')
<x-modern.card title="Product List" icon="bx bx-box">
    <x-slot name="actions">
        @can('create-product')
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Add New
        </a>
        @endcan
    </x-slot>

    <x-modern.table :headers="['#', 'Image', 'Product Name', 'Category', 'Price', 'Status', 'Actions']">
        @forelse ($products as $key => $product)
        <tr>
            <td class="align-middle text-center">{{ $key + 1 }}</td>
            <td class="align-middle">
                @if($product->images->count() > 0)
                    <img src="{{ asset($product->images->first()->image_path) }}" alt="{{ $product->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
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
                <span class="badge bg-label-primary">{{ $product->category->name }}</span>
                @if($product->subCategory)
                    <span class="badge bg-label-secondary ms-1">{{ $product->subCategory->name }}</span>
                @endif
            </td>
            <td class="align-middle">
                <span class="fw-bold text-primary">৳{{ number_format($product->sale_price, 2) }}</span>
            </td>
            <td class="align-middle text-center">
                @if($product->active_status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td class="align-middle text-center">
                <div class="d-flex justify-content-center gap-2">
                    @can('edit-product')
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary btn-sm btn-icon">
                        <i class="bx bx-edit"></i>
                    </a>
                    @endcan

                    @can('delete-product')
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm btn-icon delete-btn">
                            <i class="bx bx-trash"></i>
                        </button>
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
                <h5 class="text-muted mb-1">No Products Found</h5>
                <p class="mb-0">You haven't added any products yet.</p>
            </td>
        </tr>
        @endforelse
    </x-modern.table>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
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
