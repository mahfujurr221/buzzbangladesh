<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Order;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = Customer::where('phone', $user->phone)->first();
        
        $orders = [];
        if ($customer) {
            // Get orders for this customer, load the status relationship, order by latest
            $orders = Order::where('customer_id', $customer->id)
                           ->with('status')
                           ->latest()
                           ->get();
        }

        return view('frontend.customer.dashboard', compact('user', 'customer', 'orders'));
    }
}
