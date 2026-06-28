@props(['collection'])

@php
    $paginator = $collection;
    // Basic logic to generate page range (similar to Laravel's default pagination views)
    $window = 3; 
    $lastPage = $paginator->lastPage();
    $currentPage = $paginator->currentPage();
    
    $start = max($currentPage - $window, 1);
    $end = min($currentPage + $window, $lastPage);
@endphp

@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator || $paginator instanceof \Illuminate\Pagination\Paginator)
<div {{ $attributes->merge(['class' => 'd-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-4 border-top']) }}>
    
    {{-- Results Summary (Left Side) --}}
    <div class="text-secondary small mb-3 mb-md-0 d-flex align-items-center">
        @if($paginator->total() > 0)
            <div class="px-3 py-1 bg-light rounded-pill border border-dark border-opacity-10 d-flex align-items-center">
                <span>Showing </span>
                <span class="fw-bold mx-1 text-dark">{{ $paginator->firstItem() }}</span>
                <span>to</span>
                <span class="fw-bold mx-1 text-dark">{{ $paginator->lastItem() }}</span>
                <span>of</span>
                <span class="fw-bold mx-1 text-dark">{{ $paginator->total() }}</span>
                <span>results</span>
            </div>
        @else
            <span class="text-muted fst-italic">Showing no results</span>
        @endif
    </div>

    {{-- Pagination Controls (Right Side) --}}
    <div class="d-flex align-items-center">
        @if ($paginator->hasPages())
            <nav class="d-inline-flex modern-pagination" aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0 gap-1 align-items-center">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bx bx-chevron-left fs-5"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="width: 32px; height: 32px;">
                                <i class="bx bx-chevron-left fs-5 text-primary" style="color: #629D23 !important;"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Dynamic Page Numbers --}}
                    @if($start > 1)
                        <li class="page-item"><a class="page-link border-0 bg-transparent text-muted" href="{{ $paginator->url(1) }}">1</a></li>
                        @if($start > 2)<li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>@endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        @if ($i == $currentPage)
                            <li class="page-item active" aria-current="page">
                                <span class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold" style="width: 32px; height: 32px; background: #629D23; color: white !important;">{{ $i }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center bg-transparent text-muted fw-semibold" href="{{ $paginator->url($i) }}" style="width: 32px; height: 32px;">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)<li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>@endif
                        <li class="page-item"><a class="page-link border-0 bg-transparent text-muted" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a></li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" href="{{ $paginator->nextPageUrl() }}" rel="next" style="width: 32px; height: 32px;">
                                <i class="bx bx-chevron-right fs-5 text-primary" style="color: #629D23 !important;"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bx bx-chevron-right fs-5"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</div>
@endif

<style>
    .modern-pagination .page-link {
        transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin: 0 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #64748b;
        border: none !important;
    }
    .modern-pagination .page-link:hover:not(.disabled) {
        background-color: #f0fdf4 !important;
        color: #629D23 !important;
        transform: translateY(-2px);
    }
    .modern-pagination .page-item.active .page-link {
        color: white !important;
        box-shadow: 0 4px 12px rgba(98, 157, 35, 0.3);
    }
</style>
