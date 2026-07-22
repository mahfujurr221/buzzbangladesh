@extends('frontend.layouts.master')

@section('title', 'My Account - Buzz Bangladesh')

@section('content')
<style>
    .dashboard-wrap {
        background: #fcfcfc;
        padding: 60px 0 100px;
        min-height: calc(100vh - 250px);
    }
    .dashboard-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }
    
    /* Sidebar */
    .dashboard-sidebar {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eee;
        overflow: hidden;
        align-self: start;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .sidebar-user {
        padding: 30px 20px;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
        background: linear-gradient(to bottom, #fff, #fafafa);
    }
    .sidebar-user-avatar {
        width: 80px;
        height: 80px;
        background: #9A0002;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        margin: 0 auto 15px;
        box-shadow: 0 4px 15px rgba(154,0,2,0.2);
    }
    .sidebar-user h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    .sidebar-user p {
        font-size: 14px;
        color: #666;
    }
    .sidebar-menu {
        padding: 15px 0;
    }
    .sidebar-menu button {
        width: 100%;
        text-align: left;
        padding: 15px 25px;
        font-size: 15px;
        font-weight: 600;
        color: #555;
        background: transparent;
        border: none;
        border-left: 3px solid transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
    }
    .sidebar-menu button i {
        font-size: 20px;
        color: #888;
        transition: color 0.2s;
    }
    .sidebar-menu button:hover {
        background: #fafafa;
        color: #9A0002;
    }
    .sidebar-menu button:hover i {
        color: #9A0002;
    }
    .sidebar-menu button.active {
        background: rgba(154,0,2,0.04);
        color: #9A0002;
        border-left-color: #9A0002;
    }
    .sidebar-menu button.active i {
        color: #9A0002;
    }
    .sidebar-logout {
        border-top: 1px solid #f0f0f0;
        padding: 15px 0;
    }
    .sidebar-logout button {
        width: 100%;
        text-align: left;
        padding: 12px 25px;
        font-size: 15px;
        font-weight: 600;
        color: #e74c3c;
        background: transparent;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
    }
    .sidebar-logout button:hover {
        background: #fff0f0;
    }

    /* Content Area */
    .dashboard-content-area {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eee;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .content-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .content-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #111;
    }

    /* Profile Grid */
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .profile-item {
        background: #fafafa;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
    }
    .profile-item label {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .profile-item p {
        font-size: 16px;
        font-weight: 600;
        color: #222;
    }

    /* Orders Table */
    .orders-wrapper {
        overflow-x: auto;
    }
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    .orders-table th {
        text-align: left;
        padding: 15px;
        background: #fafafa;
        font-size: 13px;
        text-transform: uppercase;
        color: #666;
        font-weight: 700;
        border-bottom: 2px solid #eee;
    }
    .orders-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #eee;
        font-size: 15px;
        color: #333;
        vertical-align: middle;
    }
    .order-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-processing { background: #cce5ff; color: #004085; }
    .status-completed { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
    .empty-state {
        text-align: center;
        padding: 50px 0;
        color: #888;
    }
    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 15px;
    }

    @media (max-width: 900px) {
        .dashboard-container { grid-template-columns: 1fr; }
        .profile-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">My Account</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">My Account</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrap">
    <div class="container mx-auto px-4">
        
        @if(session('success'))
        <div id="success-toaster" style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 9999; display: flex; align-items: center; gap: 10px; animation: slideInRight 0.3s ease forwards;">
            <i class="ph ph-check-circle" style="font-size: 24px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
        <style>
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        </style>
        <script>
            setTimeout(() => {
                const toaster = document.getElementById('success-toaster');
                if(toaster) {
                    toaster.style.animation = 'slideOutRight 0.3s ease forwards';
                    setTimeout(() => toaster.remove(), 300);
                }
            }, 5000);
        </script>
        @endif

        <div class="dashboard-container">
            
            {{-- Sidebar --}}
            <div class="dashboard-sidebar">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr($user->fname, 0, 1)) }}
                    </div>
                    <h3>{{ $user->fullName() }}</h3>
                    <p>{{ $user->phone }}</p>
                </div>
                
                <div class="sidebar-menu">
                    <button class="{{ session('success') ? '' : 'active' }}" onclick="switchTab('profile', this)" id="btn-profile">
                        <i class="ph ph-user"></i> My Profile
                    </button>
                    <button class="{{ session('success') ? 'active' : '' }}" onclick="switchTab('orders', this)" id="btn-orders">
                        <i class="ph ph-shopping-bag"></i> Order History
                    </button>
                </div>
                
                <div class="sidebar-logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="ph ph-sign-out"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="dashboard-content-area">
                
                {{-- Profile Tab --}}
                <div id="tab-profile" class="tab-content {{ session('success') ? '' : 'active' }}">
                    <div class="content-header">
                        <h2>Personal Information</h2>
                    </div>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <label>Full Name</label>
                            <p>{{ $user->fullName() }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Phone Number</label>
                            <p>{{ $user->phone }}</p>
                        </div>
                        <div class="profile-item" style="grid-column: 1 / -1;">
                            <label>Email Address</label>
                            <p>{{ $customer && $customer->email && !str_contains($customer->email, '@buzz.local') ? $customer->email : 'Not provided' }}</p>
                        </div>
                        <div class="profile-item">
                            <label>City</label>
                            <p>{{ $customer->city ?? 'Not provided' }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Thana / Area</label>
                            <p>{{ $customer->thana ?? 'Not provided' }}</p>
                        </div>
                        <div class="profile-item" style="grid-column: 1 / -1;">
                            <label>Full Delivery Address</label>
                            <p>{{ $customer->full_address ?? 'Not provided. Update your address during your next checkout.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Orders Tab --}}
                <div id="tab-orders" class="tab-content {{ session('success') ? 'active' : '' }}">
                    <div class="content-header">
                        <h2>Order History</h2>
                    </div>
                    
                    @if(count($orders) > 0)
                        <div class="orders-wrapper">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td style="font-weight: 700; color: #9A0002;">#{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td style="font-weight: 600;">৳{{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <span class="order-status 
                                                    @if(strtolower($order->status->name ?? '') == 'completed' || strtolower($order->status->name ?? '') == 'delivered') status-completed 
                                                    @elseif(strtolower($order->status->name ?? '') == 'cancelled') status-cancelled
                                                    @elseif(strtolower($order->status->name ?? '') == 'processing') status-processing
                                                    @else status-pending @endif">
                                                    {{ $order->status->name ?? 'Pending' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('frontend.track.order', ['order' => $order->order_number]) }}" class="text-[#9A0002] font-semibold hover:underline">Track</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="ph ph-package"></i>
                            <h3>No orders yet</h3>
                            <p>When you place an order, it will appear here.</p>
                            <a href="{{ route('frontend.shop') }}" class="inline-block mt-4 px-6 py-2 bg-[#9A0002] text-white rounded-lg font-semibold">Start Shopping</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId, btnElement) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.sidebar-menu button').forEach(el => {
            el.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById('tab-' + tabId).classList.add('active');
        
        // Add active class to clicked button
        btnElement.classList.add('active');
    }
</script>
@endsection
