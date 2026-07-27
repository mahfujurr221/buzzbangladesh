@php
    $statusId = $order->order_status_id ?? 1;
    
    // Define the sequence of steps
    $steps = [
        1 => ['label' => 'Pending', 'icon' => 'ph-clock'],
        2 => ['label' => 'Order Received', 'icon' => 'ph-receipt'],
        3 => ['label' => 'Packed', 'icon' => 'ph-package'],
        4 => ['label' => 'On the way to door', 'icon' => 'ph-truck'],
        5 => ['label' => 'Delivered', 'icon' => 'ph-check-circle'],
    ];

    // If it's canceled or returned, we handle it gracefully
    $isCanceled = in_array($statusId, [6, 7]);
@endphp

@if(!$isCanceled)
<style>
    .opb-wrapper {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 12px;
        margin-bottom: 24px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .opb-wrapper::-webkit-scrollbar { display: none; }
    .opb-steps {
        display: inline-flex;
        align-items: center;
        gap: 0;
        min-width: max-content;
        margin: 0 auto;
    }
    .opb-step {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #aaa;
    }
    .opb-step.active { color: var(--brand); }
    .opb-step.done { color: var(--brand); } 
    .opb-step.done .opb-num {
        background: var(--brand);
        border-color: var(--brand);
        color: white;
    }
    .opb-step.done .opb-icon { font-size: 16px; }
    .opb-num {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        font-weight: 700;
        border: 1.5px solid #ddd;
        background: #fff;
        color: #aaa;
        flex-shrink: 0;
    }
    .opb-step.active .opb-num {
        border-color: var(--brand);
        color: var(--brand);
    }
    .opb-divider {
        width: 40px;
        height: 2px;
        background: #eee;
        margin: 0 10px;
        border-radius: 2px;
    }
    
    @media (max-width: 768px) {
        .opb-step span {
            font-size: 11px;
        }
        .opb-num {
            width: 26px; height: 26px;
            font-size: 14px;
        }
        .opb-divider {
            width: 20px;
            margin: 0 5px;
        }
    }
</style>

<div class="opb-wrapper mt-6">
    <div class="opb-steps w-full justify-center">
        @foreach($steps as $id => $step)
            @php
                $isDone = $statusId > $id || $statusId == 5;
                $isActive = $statusId == $id;
                $stateClass = $isDone ? 'done' : ($isActive ? 'active' : '');
            @endphp
            <div class="opb-step {{ $stateClass }}">
                <div class="opb-num">
                    @if($isDone)
                        <i class="ph-bold ph-check opb-icon"></i>
                    @else
                        <i class="ph {{ $step['icon'] }} opb-icon"></i>
                    @endif
                </div>
                <span>{{ $step['label'] }}</span>
            </div>
            
            @if(!$loop->last)
                <div class="opb-divider" style="{{ $isDone ? 'background: var(--brand);' : '' }}"></div>
            @endif
        @endforeach
    </div>
</div>
@else
<div class="alert alert-danger mt-6 text-center rounded-xl p-4 text-red-600 border border-red-200" style="background-color: #fef2f2; color: #dc2626; border-color: #fecaca; margin-bottom: 24px;">
    <div class="font-bold text-lg mb-1 flex items-center justify-center gap-2"><i class="ph-fill ph-warning-circle text-xl"></i> Order {{ $statusId == 6 ? 'Canceled' : 'Returned' }}</div>
    <p class="text-sm">This order has been {{ $statusId == 6 ? 'canceled' : 'returned' }} and cannot be tracked further.</p>
</div>
@endif
