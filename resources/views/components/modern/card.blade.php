@props([
'title' => null,
'subtitle' => null,
'icon' => null,
'iconClass' => 'text-primary',
'headerClass' => '',
'bodyClass' => '',
'noPadding' => false,
'footer' => null
])

<div {{ $attributes->merge(['class' => 'card modern-card border-0 shadow-sm mb-3']) }} style="border-radius: 12px;
    overflow: hidden;">
    @if($title || $slot->isNotEmpty() || $icon)
    <div class="card-header bg-transparent border-0 pt-2 pb-0 px-3 {{ $headerClass }}">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center py-1">
                @if($icon)
                <div class="modern-card-icon me-2 d-flex align-items-center justify-content-center"
                    style="width: 32px; height: 32px; background: rgba(var(--bs-primary-rgb), 0.1); border-radius: 8px;">
                    <i class="{{ $icon }} font-size-16 {{ $iconClass }}"></i>
                </div>
                @endif
                <div>
                    @if($title)
                    <h5 class="card-title mb-0 fw-bold font-size-14">{{ $title }}</h5>
                    @endif
                    @if($subtitle)
                    <p class="text-muted mb-0 font-size-12">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <div class="card-actions">
                {{ $actions ?? '' }}
            </div>
        </div>
    </div>
    @endif

    <div @class(['card-body', 'p-0'=> $noPadding, 'p-3' => !$noPadding, $bodyClass])>
        {{ $slot }}
    </div>

    @if($footer)
    <div class="card-footer bg-light border-0 py-3 px-4">
        {{ $footer }}
    </div>
    @endif
</div>

<style>
    .modern-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.2) !important;
    }

    .modern-card:hover {
        /* transform: translateY(-1px); */
        box-shadow: 0 6px 12px rgba(var(--bs-primary-rgb), 0.3) !important;
    }
</style>