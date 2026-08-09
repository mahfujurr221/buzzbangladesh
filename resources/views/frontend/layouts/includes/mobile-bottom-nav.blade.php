<div class="buzz-bottom-nav lg:hidden" style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 9999; background-color: #ffffff; border-top: 1px solid #e5e7eb; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
    <div style="display: flex; width: 100%; align-items: center; justify-content: space-between; padding-bottom: max(0px, env(safe-area-inset-bottom)); background-color: #ffffff;">
        
        <!-- Home -->
        <a href="{{ route('frontend.home') }}" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px 0; text-decoration: none; color: {{ request()->routeIs('frontend.home') ? '#9A0002' : '#6b7280' }};">
            <i class="{{ request()->routeIs('frontend.home') ? 'ph-fill' : 'ph' }} ph-house" style="font-size: 24px; margin-bottom: 4px;"></i>
            <span style="font-size: 11px; font-weight: 500; line-height: 1;">Home</span>
        </a>

        <!-- Search -->
        <a href="javascript:void(0)" class="search-icon" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px 0; text-decoration: none; color: #6b7280;">
            <i class="ph ph-magnifying-glass" style="font-size: 24px; margin-bottom: 4px;"></i>
            <span style="font-size: 11px; font-weight: 500; line-height: 1;">Search</span>
        </a>

        <!-- Deals (Conditional) -->
        @if(!empty($hasActiveDeals))
        <a href="{{ route('frontend.shop', ['filter' => 'hot-deals']) }}" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px 0; text-decoration: none; color: {{ request()->get('filter') === 'hot-deals' ? '#9A0002' : '#6b7280' }};">
            <i class="{{ request()->get('filter') === 'hot-deals' ? 'ph-fill' : 'ph' }} ph-tag" style="font-size: 24px; margin-bottom: 4px;"></i>
            <span style="font-size: 11px; font-weight: 500; line-height: 1;">Deals</span>
        </a>
        @endif

        <!-- Cart -->
        <a href="javascript:void(0)" class="open-cart-modal" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px 0; text-decoration: none; color: #6b7280;">
            <div style="position: relative; display: flex; align-items: center; justify-content: center; margin-bottom: 4px;">
                <i class="ph ph-shopping-cart" style="font-size: 24px;"></i>
                <span class="quantity cart-quantity" style="position: absolute; right: -8px; top: -4px; font-size: 10px; font-weight: 600; color: #ffffff; height: 16px; min-width: 16px; padding: 0 4px; display: flex; align-items: center; justify-content: center; border-radius: 9999px; background-color: #ef4444; line-height: 1;">0</span>
            </div>
            <span style="font-size: 11px; font-weight: 500; line-height: 1;">Cart</span>
        </a>

        <!-- My account -->
        <a href="{{ route('frontend.customer.dashboard') }}" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px 0; text-decoration: none; color: {{ request()->routeIs('frontend.customer.*') || request()->routeIs('login') ? '#9A0002' : '#6b7280' }};">
            <i class="{{ request()->routeIs('frontend.customer.*') || request()->routeIs('login') ? 'ph-fill' : 'ph' }} ph-user" style="font-size: 24px; margin-bottom: 4px;"></i>
            <span style="font-size: 11px; font-weight: 500; line-height: 1; width: 100%; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">My account</span>
        </a>

    </div>
</div>

<style>
    /* Add padding to body to prevent content from being hidden behind the fixed nav on mobile */
    @media (max-width: 1023px) {
        body {
            /* Account for the height of the nav + safe area on iOS devices */
            padding-bottom: calc(65px + env(safe-area-inset-bottom)) !important;
        }
        
        /* Ensure modals stay above the bottom nav */
        .modal-overlay, .modal-cart-block, .modal-search-block {
            z-index: 10000 !important;
        }
    }
</style>
