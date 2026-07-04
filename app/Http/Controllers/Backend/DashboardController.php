<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = \App\Models\Order::whereNotIn('order_status_id', function($query) {
            $query->select('id')->from('order_statuses')->whereIn('name', ['Canceled', 'Returned']);
        })->sum('total_amount');

        $totalOrders = \App\Models\Order::count();
        
        $pendingStatusId = \App\Models\OrderStatus::where('name', 'Pending')->value('id');
        $pendingOrders = \App\Models\Order::where('order_status_id', $pendingStatusId)->count();

        $totalCustomers = \App\Models\Customer::count();
        $totalProducts = \App\Models\Product::where('active_status', 1)->count();

        $recentOrders = \App\Models\Order::with(['customer', 'status'])
            ->latest()
            ->take(5)
            ->get();

        return view('backend.dashboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'pendingOrders', 
            'totalCustomers', 
            'totalProducts', 
            'recentOrders'
        ));
    }
}
