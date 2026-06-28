@props([
    'title' => 'Filter Data',
    'icon' => 'bx bx-filter-alt',
    'action' => null,
    'resetUrl' => null,
    'method' => 'GET',
    'collapsible' => true,
    'expanded' => false
])

<div {{ $attributes->merge(['class' => 'card modern-filter border-0 shadow-sm overflow-hidden mb-3']) }} style="border-radius: 12px;">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-2 px-3">
        <div class="d-flex align-items-center flex-grow-1" @if($collapsible) data-bs-toggle="collapse" data-bs-target="#filterCollapse" style="cursor: pointer;" @endif>
            <i class="{{ $icon }} me-2" style="color: #629D23; font-size: 1.1rem;"></i>
            <h5 class="card-title mb-0 fw-bold text-dark text-uppercase d-none d-sm-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ $title }}</h5>
            @if($collapsible)
                <i class="bx bx-chevron-down filter-chevron ms-2 {{ $expanded ? 'rotate-180' : '' }}" style="transition: transform 0.3s ease; font-size: 1rem; color: #64748b;"></i>
            @endif
        </div>

        <div class="header-actions d-flex gap-2 align-items-center">
            @if($resetUrl)
                <x-modern.actions.button tag="a" href="{{ $resetUrl }}" label="Reset" variant="secondary" size="sm" icon="bx bx-reset" />
            @else
                <x-modern.actions.button type="button" label="Reset" variant="secondary" size="sm" icon="bx bx-reset" onclick="document.querySelector('#filter-form').reset()" />
            @endif
            <x-modern.actions.button type="button" label="Filter" variant="primary" size="sm" icon="bx bx-search-alt" onclick="document.querySelector('#filter-form').submit()" />
        </div>
    </div>

    <div id="filterCollapse" class="collapse {{ $expanded ? 'show' : '' }}">
        <div class="card-body px-3 pb-3 pt-2 border-top border-light">
            <form action="{{ $action }}" method="{{ $method }}" id="filter-form">
                <div class="row g-2 align-items-end">
                    {{ $slot }}
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .filter-chevron.rotate-180 {
        transform: rotate(180deg);
    }

    .modern-filter {
        background-color: #fff;
        border-left: 4px solid #629D23 !important;
    }

    .modern-filter .form-label {
        font-size: 0.65rem !important;
        font-weight: 800 !important;
        margin-bottom: 0.3rem !important;
        color: #475569 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-filter .form-control, 
    .modern-filter .form-select,
    .modern-filter .input-group-text {
        padding: 0.35rem 0.75rem !important;
        font-size: 0.8rem !important;
        border-radius: 8px !important;
        background-color: #f8fafc !important;
    }

    .modern-filter .input-group-text {
        display: none !important;
    }

    .modern-filter .form-control, 
    .modern-filter .form-select {
        border-radius: 8px !important;
        border-left: 1px solid #e2e8f0 !important;
    }

    .modern-filter .card-body {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
</style>