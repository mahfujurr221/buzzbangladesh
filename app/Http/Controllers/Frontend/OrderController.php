<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariation;

class OrderController extends Controller
{
    /**
     * Place an order from the cart session.
     * Runs in a DB transaction so stock deductions are atomic.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:100',
            'city'    => 'required|string|max:100',
            'thana'   => 'required|string|max:100',
            'address' => 'required|string',
            'notes'   => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Your cart is empty. Please add products before placing an order.',
            ], 422);
        }

        try {
            $result = DB::transaction(function () use ($request, $cart) {

                // ──────────────────────────────────────────────────────────
                // 1. VALIDATE STOCK before touching anything
                // ──────────────────────────────────────────────────────────
                foreach ($cart as $cartKey => $item) {
                    if ($item['color_id'] || $item['size_id']) {
                        // Find the exact variation — lock the row so concurrent
                        // orders don't oversell
                        $variation = ProductVariation::where('product_id', $item['id'])
                            ->when($item['color_id'], fn($q) => $q->where('product_color_id', $item['color_id']))
                            ->when($item['size_id'],  fn($q) => $q->where('product_size_id',  $item['size_id']))
                            ->where('active_status', true)
                            ->lockForUpdate()
                            ->first();

                        if (!$variation) {
                            throw new \Exception("Variation not found for product: {$item['name']}");
                        }

                        if ($variation->stock_quantity < $item['quantity']) {
                            throw new \Exception(
                                "Not enough stock for \"{$item['name']}\"" .
                                ($item['color_name'] ? " ({$item['color_name']}" : '') .
                                ($item['size_name']  ? " / {$item['size_name']})" : ($item['color_name'] ? ')' : '')) .
                                ". Available: {$variation->stock_quantity}"
                            );
                        }

                    } else {
                        // No variation — nothing to stock-check at variation level
                        // (product has no variations, which is valid)
                    }
                }

                // ──────────────────────────────────────────────────────────
                // 2. FIND OR CREATE CUSTOMER  (keyed by phone)
                // ──────────────────────────────────────────────────────────
                $customer = Customer::firstOrNew(['phone' => $request->phone]);
                $customer->name         = $request->name;
                $customer->email        = $request->email;
                $customer->city         = $request->city;
                $customer->thana        = $request->thana;
                $customer->full_address = $request->address;
                $customer->save();

                // ──────────────────────────────────────────────────────────
                // 3. CALCULATE TOTALS
                // ──────────────────────────────────────────────────────────
                $totalAmount       = 0;
                $totalPurchaseCost = 0;

                foreach ($cart as $item) {
                    $totalAmount       += $item['price'] * $item['quantity'];
                    $totalPurchaseCost += ($item['purchase_price'] ?? 0) * $item['quantity'];
                }

                $shippingCost = 0; // COD, free shipping for now
                $netProfit    = $totalAmount - $totalPurchaseCost - $shippingCost;

                // ──────────────────────────────────────────────────────────
                // 4. GENERATE ORDER NUMBER
                // ──────────────────────────────────────────────────────────
                $defaultStatus = OrderStatus::where('is_default', true)->first();
                if (!$defaultStatus) {
                    $defaultStatus = OrderStatus::first();
                }

                $orderNumber = 'BZ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                // ──────────────────────────────────────────────────────────
                // 5. CREATE ORDER
                // ──────────────────────────────────────────────────────────
                $order = Order::create([
                    'order_number'       => $orderNumber,
                    'customer_id'        => $customer->id,
                    'order_status_id'    => $defaultStatus->id,
                    'total_amount'       => $totalAmount,
                    'shipping_cost'      => $shippingCost,
                    'total_purchase_cost'=> $totalPurchaseCost,
                    'net_profit'         => $netProfit,
                    'city'               => $request->city,
                    'thana'              => $request->thana,
                    'shipping_address'   => $request->address,
                    'payment_method'     => 'cod',
                    'notes'              => $request->notes,
                ]);

                // ──────────────────────────────────────────────────────────
                // 6. CREATE ORDER ITEMS + DEDUCT STOCK
                // ──────────────────────────────────────────────────────────
                foreach ($cart as $cartKey => $item) {
                    $variationId  = null;
                    $purchaseCost = $item['purchase_price'] ?? 0;

                    if ($item['color_id'] || $item['size_id']) {
                        // Re-fetch with lock (already validated above, but lock again
                        // to get the authoritative row inside the same transaction)
                        $variation = ProductVariation::where('product_id', $item['id'])
                            ->when($item['color_id'], fn($q) => $q->where('product_color_id', $item['color_id']))
                            ->when($item['size_id'],  fn($q) => $q->where('product_size_id',  $item['size_id']))
                            ->where('active_status', true)
                            ->lockForUpdate()
                            ->firstOrFail();

                        // Deduct stock atomically
                        $variation->decrement('stock_quantity', $item['quantity']);

                        $variationId  = $variation->id;
                        $purchaseCost = $variation->purchase_price ?? $purchaseCost;
                    }

                    $unitPrice  = $item['price'];
                    $totalPrice = $unitPrice * $item['quantity'];
                    $totalCost  = $purchaseCost * $item['quantity'];

                    OrderItem::create([
                        'order_id'            => $order->id,
                        'product_id'          => $item['id'],
                        'product_variation_id'=> $variationId,
                        'product_name'        => $item['name'],
                        'color_name'          => $item['color_name'] ?? null,
                        'size_name'           => $item['size_name']  ?? null,
                        'quantity'            => $item['quantity'],
                        'unit_price'          => $unitPrice,
                        'purchase_cost'       => $purchaseCost,
                        'total_price'         => $totalPrice,
                        'total_profit'        => $totalPrice - $totalCost,
                    ]);
                }

                // ──────────────────────────────────────────────────────────
                // 7. CLEAR CART
                // ──────────────────────────────────────────────────────────
                session()->forget('cart');

                return $order;
            });

            return response()->json([
                'status'       => 'success',
                'message'      => 'Order placed successfully!',
                'order_number' => $result->order_number,
                'redirect'     => route('frontend.order.success', $result->order_number),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Order success page.
     */
    public function success($orderNumber)
    {
        $order = Order::with(['customer', 'status', 'items'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('frontend.order-success', compact('order'));
    }

    /**
     * Track an order by order number.
     */
    public function trackOrder(Request $request)
    {
        $order = null;
        if ($request->has('order_number')) {
            $orderNumber = $request->input('order_number');
            $order = Order::with(['status', 'items', 'customer'])->where('order_number', $orderNumber)->first();
            
            if (!$order) {
                return redirect()->route('frontend.track.order')->with('error', 'Order not found with the provided order number.');
            }
        }
        
        return view('frontend.track-order', compact('order'));
    }
}
