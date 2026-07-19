<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Customer;
use App\Models\ProductVariation;
use App\Models\OrderItem;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

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
        $order->load(['customer', 'items.variation.product', 'items.variation.color', 'items.variation.size', 'status']);
        $allStatuses = OrderStatus::all();
        return view('backend.pages.order.show', compact('order', 'allStatuses'));
    }

    public function invoice(Order $order)
    {
        $order->load(['customer', 'items.variation.product', 'items.variation.color', 'items.variation.size', 'status']);
        return view('backend.pages.order.invoice', compact('order'));
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
    public function create()
    {
        return view('backend.pages.order.create');
    }

    public function searchProducts(Request $request)
    {
        $search = $request->q;
        
        $variations = ProductVariation::with(['product', 'size', 'color'])
            ->where('active_status', 1)
            ->whereHas('product', function($q) use ($search) {
                $q->where('active_status', 1);
                if ($search) {
                    $q->where('name', 'like', "%{$search}%");
                }
            });
            
        if ($search) {
            $variations->orWhere('sku', 'like', "%{$search}%");
        }
        
        // Limit to 20 to keep it fast
        $results = $variations->take(20)->get()->map(function($variation) {
            $productName = $variation->product->name;
            $attrs = [];
            if ($variation->size) $attrs[] = $variation->size->name;
            if ($variation->color) $attrs[] = $variation->color->name;
            
            $attrStr = count($attrs) > 0 ? ' (' . implode(' | ', $attrs) . ')' : '';
            
            return [
                'id' => $variation->id,
                'name' => $productName . $attrStr,
                'sku' => $variation->sku,
                'price' => $variation->sale_price ?? $variation->regular_price,
                'stock' => $variation->stock_quantity,
                'purchase_price' => $variation->purchase_price,
                'image' => $variation->product->primary_image ? asset('uploads/products/' . $variation->product->primary_image) : null
            ];
        });
        
        return response()->json($results);
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->q;
        
        $query = Customer::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }
        
        $customers = $query->take(20)->get()->map(function($customer) {
            return [
                'id' => $customer->id,
                'text' => $customer->name . ' - ' . $customer->phone,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'city' => $customer->city,
                'thana' => $customer->thana,
                'address' => $customer->full_address
            ];
        });
        
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable',
            'customer_name' => 'required_without:customer_id',
            'customer_phone' => 'required_without:customer_id',
            'cart' => 'required|array|min:1',
            'shipping_cost' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // 1. Resolve Customer
            if ($request->customer_id && is_numeric($request->customer_id)) {
                $customerId = $request->customer_id;
            } else {
                $customer = Customer::firstOrCreate(
                    ['phone' => $request->customer_phone],
                    [
                        'name' => $request->customer_name,
                        'city' => $request->city,
                        'thana' => $request->thana,
                        'full_address' => $request->shipping_address
                    ]
                );
                $customerId = $customer->id;
            }

            // 2. Default Status
            $status = OrderStatus::where('name', 'Pending')->first();

            // 3. Totals Calculation & Items prep
            $totalAmount = 0;
            $totalPurchaseCost = 0;
            $itemsData = [];

            foreach ($request->cart as $item) {
                $variation = ProductVariation::findOrFail($item['id']);
                
                $qty = (int) $item['qty'];
                if ($qty <= 0) continue;

                $unitPrice = $item['price'];
                $purchaseCost = $variation->purchase_price ?? 0;
                
                $lineTotal = $qty * $unitPrice;
                $linePurchaseTotal = $qty * $purchaseCost;
                
                $totalAmount += $lineTotal;
                $totalPurchaseCost += $linePurchaseTotal;

                $itemsData[] = [
                    'variation' => $variation,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'purchase_cost' => $purchaseCost,
                    'total_price' => $lineTotal,
                    'total_profit' => $lineTotal - $linePurchaseTotal,
                ];
            }

            $shippingCost = $request->shipping_cost ?? 0;
            $grandTotal = $totalAmount + $shippingCost;
            $netProfit = $totalAmount - $totalPurchaseCost; // Excluding shipping as it's pass-through

            // 4. Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_id' => $customerId,
                'order_status_id' => $status->id ?? 1,
                'total_amount' => $grandTotal,
                'shipping_cost' => $shippingCost,
                'total_purchase_cost' => $totalPurchaseCost,
                'net_profit' => $netProfit,
                'city' => $request->city,
                'thana' => $request->thana,
                'shipping_address' => $request->shipping_address,
            ]);

            // 5. Create Items and Deduct Stock
            foreach ($itemsData as $data) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variation_id' => $data['variation']->id,
                    'quantity' => $data['qty'],
                    'unit_price' => $data['unit_price'],
                    'purchase_cost' => $data['purchase_cost'],
                    'total_price' => $data['total_price'],
                    'total_profit' => $data['total_profit']
                ]);

                // Deduct stock
                $data['variation']->decrement('stock_quantity', $data['qty']);
            }

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully!',
                'redirect' => route('orders.invoice', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }
}
