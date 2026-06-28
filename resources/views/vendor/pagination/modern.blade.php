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
                        <i class="bx bx-chevron-left fs-5 text-primary"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link border-0 bg-transparent px-2 text-muted">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold" style="width: 32px; height: 32px; background: #629D23;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center bg-transparent text-muted fw-semibold" href="{{ $url }}" style="width: 32px; height: 32px;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" href="{{ $paginator->nextPageUrl() }}" rel="next" style="width: 32px; height: 32px;">
                        <i class="bx bx-chevron-right fs-5 text-primary"></i>
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

<style>
    .modern-pagination .page-link {
        transition: all 0.2s ease;
        margin: 0 2px;
    }
    .modern-pagination .page-link:hover:not(.disabled) {
        background-color: #f0fdf4 !important;
        color: #629D23 !important;
        transform: translateY(-2px);
    }
    .modern-pagination .page-item.active .page-link {
        color: white !important;
    }
</style>
