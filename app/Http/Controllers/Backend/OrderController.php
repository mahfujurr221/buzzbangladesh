<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatus;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-online-order', ['only' => ['onlineOrders']]);
        $this->middleware('can:list-sale-order', ['only' => ['sales']]);
        $this->middleware('can:list-canceled-order', ['only' => ['canceledOrders']]);
        $this->middleware('can:list-returned-order', ['only' => ['returnedOrders']]);
        $this->middleware('can:view-order', ['only' => ['show']]);
    }

    public function onlineOrders(Request $request)
    {
        $statuses = OrderStatus::whereIn('name', ['Pending', 'Received', 'Packed', 'Shipped'])->pluck('id');
        $query = Order::with('customer', 'status')->whereIn('order_status_id', $statuses);

        if ($request->filled('order_id')) {
            $query->where('order_number', 'like', "%{$request->order_id}%");
        }
        if ($request->filled('customer_info')) {
            $customerInfo = $request->customer_info;
            $query->whereHas('customer', function($cq) use ($customerInfo) {
                $cq->where('name', 'like', "%{$customerInfo}%")
                   ->orWhere('phone', 'like', "%{$customerInfo}%");
            });
        }
        if ($request->filled('product_name')) {
            $productName = $request->product_name;
            $query->whereHas('items.variation.product', function($pq) use ($productName) {
                $pq->where('name', 'like', "%{$productName}%");
            });
        }

        $orders = $query->latest()->paginate(20);
        $allStatuses = OrderStatus::all();

        return view('backend.pages.order.online_orders', compact('orders', 'allStatuses'));
    }

    public function sales(Request $request)
    {
        $status = OrderStatus::where('name', 'Delivered')->first();
        $query = Order::with('customer', 'status')->where('order_status_id', $status->id ?? -1);

        if ($request->filled('order_id')) {
            $query->where('order_number', 'like', "%{$request->order_id}%");
        }
        if ($request->filled('customer_info')) {
            $customerInfo = $request->customer_info;
            $query->whereHas('customer', function($cq) use ($customerInfo) {
                $cq->where('name', 'like', "%{$customerInfo}%")
                   ->orWhere('phone', 'like', "%{$customerInfo}%");
            });
        }
        if ($request->filled('product_name')) {
            $productName = $request->product_name;
            $query->whereHas('items.variation.product', function($pq) use ($productName) {
                $pq->where('name', 'like', "%{$productName}%");
            });
        }

        $orders = $query->latest()->paginate(20);
        $allStatuses = OrderStatus::all();

        return view('backend.pages.order.sales', compact('orders', 'allStatuses'));
    }

    public function canceledOrders(Request $request)
    {
        $status = OrderStatus::where('name', 'Canceled')->first();
        $query = Order::with('customer', 'status')->where('order_status_id', $status->id ?? -1);

        if ($request->filled('order_id')) {
            $query->where('order_number', 'like', "%{$request->order_id}%");
        }
        if ($request->filled('customer_info')) {
            $customerInfo = $request->customer_info;
            $query->whereHas('customer', function($cq) use ($customerInfo) {
                $cq->where('name', 'like', "%{$customerInfo}%")
                   ->orWhere('phone', 'like', "%{$customerInfo}%");
            });
        }
        if ($request->filled('product_name')) {
            $productName = $request->product_name;
            $query->whereHas('items.variation.product', function($pq) use ($productName) {
                $pq->where('name', 'like', "%{$productName}%");
            });
        }

        $orders = $query->latest()->paginate(20);
        $allStatuses = OrderStatus::all();

        return view('backend.pages.order.canceled_orders', compact('orders', 'allStatuses'));
    }

    public function returnedOrders(Request $request)
    {
        $status = OrderStatus::where('name', 'Returned')->first();
        $query = Order::with('customer', 'status')->where('order_status_id', $status->id ?? -1);

        if ($request->filled('order_id')) {
            $query->where('order_number', 'like', "%{$request->order_id}%");
        }
        if ($request->filled('customer_info')) {
            $customerInfo = $request->customer_info;
            $query->whereHas('customer', function($cq) use ($customerInfo) {
                $cq->where('name', 'like', "%{$customerInfo}%")
                   ->orWhere('phone', 'like', "%{$customerInfo}%");
            });
        }
        if ($request->filled('product_name')) {
            $productName = $request->product_name;
            $query->whereHas('items.variation.product', function($pq) use ($productName) {
                $pq->where('name', 'like', "%{$productName}%");
            });
        }

        $orders = $query->latest()->paginate(20);
        $allStatuses = OrderStatus::all();

        return view('backend.pages.order.returned_orders', compact('orders', 'allStatuses'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.variation.color', 'items.variation.size', 'status']);
        $allStatuses = OrderStatus::all();
        
        return view('backend.pages.order.show', compact('order', 'allStatuses'));
    }

    public function changeStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id'
        ]);

        $newStatus = OrderStatus::find($request->order_status_id);
        $permissionName = 'change-status-' . strtolower($newStatus->name);

        if (!auth()->user()->can($permissionName)) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'You do not have permission to change status to ' . $newStatus->name], 403);
            }
            toast('You do not have permission to change status to ' . $newStatus->name, 'error');
            return back();
        }

        $order->order_status_id = $newStatus->id;
        $order->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order status updated successfully.',
                'new_status' => $order->status->name,
                'color_code' => $order->status->color_code
            ]);
        }

        toast('Order status updated successfully.', 'success');
        return back();
    }
}
