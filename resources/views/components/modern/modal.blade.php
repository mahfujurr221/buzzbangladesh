@props([
    'id',
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'size' => 'md', // sm, md, lg, xl, fullscreen
    'scrollable' => true,
    'centered' => true,
    'footer' => null,
    'static' => true, 
    'backdrop' => false, // Default false as requested
    'variant' => 'primary',
    'show' => false
])

@php
    // Consolidated array for cleaner code
    $variants = [
        'primary' => ['bg' => 'linear-gradient(135deg, #629D23 0%, #8CBF3F 100%)', 'text' => 'text-white', 'close' => 'btn-close-white', 'iconBg' => 'bg-white bg-opacity-20'],
        'success' => ['bg' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)', 'text' => 'text-white', 'close' => 'btn-close-white', 'iconBg' => 'bg-white bg-opacity-20'],
        'info'    => ['bg' => 'linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%)', 'text' => 'text-white', 'close' => 'btn-close-white', 'iconBg' => 'bg-white bg-opacity-20'],
        'warning' => ['bg' => 'linear-gradient(135deg, #fceeb5 0%, #f7d794 100%)', 'text' => 'text-dark',  'close' => '',                'iconBg' => 'bg-dark bg-opacity-10'],
        'danger'  => ['bg' => 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)', 'text' => 'text-white', 'close' => 'btn-close-white', 'iconBg' => 'bg-white bg-opacity-20'],
        'dark'    => ['bg' => 'linear-gradient(135deg, #232526 0%, #414345 100%)', 'text' => 'text-white', 'close' => 'btn-close-white', 'iconBg' => 'bg-white bg-opacity-20'],
    ];
    $style = $variants[$variant] ?? $variants['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'modal fade modern-modal']) }}
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}Label"
    aria-hidden="true"
    @if(!$backdrop) data-bs-backdrop="false" @endif
    @if($static) data-bs-backdrop="static" data-bs-keyboard="false" @endif
    @if($show) data-state="show" @endif>
    
    <div class="modal-dialog {{ 'modal-' . $size }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }} {{ $centered ? 'modal-dialog-centered' : '' }}">
        <div class="modal-content border-0 shadow-lg modern-modal-content">
            
            {{-- Header Section --}}
            @if($title || $icon)
                <div class="modal-header border-0 px-4 py-3" style="background: {{ $style['bg'] }};">
                    <div class="d-flex align-items-center">
                        @if($icon)
                            <div class="modal-icon-container me-3 d-flex align-items-center justify-content-center {{ $style['iconBg'] }} rounded-circle">
                                <i class="{{ $icon }} fs-4 {{ $style['text'] }}"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="modal-title fw-bold mb-0 {{ $style['text'] }}" id="{{ $id }}Label">{{ $title }}</h5>
                            @if($subtitle)
                                <small class="opacity-75 d-block line-height-sm mt-1 {{ $style['text'] }}" style="font-size: 0.8rem;">{{ $subtitle }}</small>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn-close {{ $style['close'] }} opacity-75" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            {{-- Body Section --}}
            <div class="modal-body p-4 text-secondary">
                {{ $slot }}
            </div>

            {{-- Footer Section --}}
            @if($footer || isset($footerActions) || isset($footerLeft))
                <div class="modal-footer border-top bg-light bg-opacity-50 px-4 py-3 d-flex justify-content-between">
                    <div>
                        {{ $footerLeft ?? '' }}
                    </div>
                    <div class="d-flex gap-2">
                        {{ $footerActions ?? $footer }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Apply the blur directly to the modal wrapper. 
       This creates a blurry background even when data-bs-backdrop="false" 
    */
    .modern-modal {
        backdrop-filter: blur(8px);
        background-color: rgba(0, 0, 0, 0.25); /* Subtle tint so the modal pops */
    }

    .modern-modal-content {
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        transform: translateY(30px) scale(0.96);
        opacity: 0;
    }

    .modern-modal.show .modern-modal-content {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    /* Header adjustments */
    .modern-modal .modal-header {
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .modern-modal .modal-title {
        letter-spacing: -0.5px;
    }

    /* Icon Container & Animation */
    .modern-modal .modal-icon-container {
        width: 44px; 
        height: 44px;
    }

    .modern-modal.show .modal-icon-container {
        animation: modalIconEntry 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
    }

    @keyframes modalIconEntry {
        0% { transform: scale(0.5) rotate(-45deg); opacity: 0; }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }

    /* Close Button Hover */
    .modern-modal .btn-close {
        transition: all 0.2s ease;
        padding: 1rem;
        border-radius: 50px;
    }

    .modern-modal .btn-close:hover {
        transform: rotate(90deg);
        background-color: rgba(255, 255, 255, 0.1);
        opacity: 1;
    }

    .modern-modal .modal-footer {
        border-color: rgba(0, 0, 0, 0.05) !important;
    }
</style>