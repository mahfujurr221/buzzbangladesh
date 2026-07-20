<x-modern.table :headers="['Order #', 'Customer', 'Date', 'Total Amount', 'Status', 'Actions']" tableClass="text-center">
    @forelse($orders as $order)
        <tr>
            <td class="align-middle fw-bold text-dark">
                <a href="{{ route('orders.show', $order->id) }}">#{{ $order->order_number }}</a>
            </td>
            <td class="align-middle">
                {{ $order->customer->name ?? 'Unknown' }}
                <div class="small text-muted">{{ $order->customer->phone ?? '' }}</div>
            </td>
            <td class="align-middle">
                {{ $order->created_at->format('d M, Y') }}
                <div class="small text-muted">{{ $order->created_at->format('h:i A') }}</div>
            </td>
            <td class="align-middle fw-bold text-primary">
                ৳{{ number_format($order->total_amount, 2) }}
            </td>
            <td class="align-middle">
                @php
                    $canChangeAny = false;
                    foreach($allStatuses as $status) {
                        if (auth()->user()->can('change-status-' . strtolower($status->name))) {
                            $canChangeAny = true;
                            break;
                        }
                    }
                @endphp
                @if($canChangeAny)
                    <select class="form-select form-select-sm d-inline-block w-auto border-0 fw-bold status-changer" 
                            data-order-id="{{ $order->id }}" 
                            style="background-color: {{ $order->status->color_code }}20; color: {{ $order->status->color_code }}; border-radius: 8px;">
                        @foreach($allStatuses as $status)
                            {{-- Always show the current status as an option, plus any they have permission to change to --}}
                            @if($order->order_status_id == $status->id || auth()->user()->can('change-status-' . strtolower($status->name)))
                            <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                @else
                    <span class="badge" style="background-color: {{ $order->status->color_code }}20; color: {{ $order->status->color_code }}; border-radius: 8px;">
                        {{ $order->status->name }}
                    </span>
                @endif
            </td>
            <td class="align-middle">
                <div class="d-flex justify-content-center gap-2">
                    @can('view-order')
                    <x-modern.actions.button tag="a" href="{{ route('orders.show', $order->id) }}" actionType="edit" icon="bx bx-show" label="View" outline />
                    <x-modern.actions.button tag="a" href="{{ route('orders.invoice', $order->id) }}" target="_blank" variant="secondary" size="sm" icon="bx bx-printer" label="Invoice" outline />
                    @endcan
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center p-5 text-muted">
                <div class="mb-3">
                    <i class="bx bx-shopping-bag text-light" style="font-size: 80px;"></i>
                </div>
                <h5 class="fw-bold">No Orders Found</h5>
                <p class="text-muted mb-0">There are currently no orders in this category.</p>
            </td>
        </tr>
    @endforelse
</x-modern.table>

<x-modern.pagination :collection="$orders" />

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusChangers = document.querySelectorAll('.status-changer');
    
    statusChangers.forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.dataset.orderId;
            const newStatusId = this.value;
            const originalColor = this.style.color;
            const originalBg = this.style.backgroundColor;
            
            // Visual loading state
            this.style.opacity = '0.5';
            
            fetch(`/back/orders/${orderId}/change-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_status_id: newStatusId })
            })
            .then(response => response.json())
            .then(data => {
                this.style.opacity = '1';
                if(data.status === 'success') {
                    // Update colors based on the response
                    this.style.color = data.color_code;
                    this.style.backgroundColor = data.color_code + '20';
                    
                    // Show a toast or alert (assuming toastr or similar is available)
                    if(typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    }
                }
            })
            .catch(error => {
                this.style.opacity = '1';
                console.error('Error:', error);
                alert('An error occurred while updating the status.');
            });
        });
    });
});
</script>
@endpush
