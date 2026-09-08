<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SteadfastWebhookController extends Controller
{
    /**
     * Handle incoming Steadfast Webhook.
     */
    public function handle(Request $request)
    {
        Log::info('Steadfast Webhook Received:', $request->all());

        // 0. Security Token Check (Bearer Token or ?token= query param)
        $bearerToken   = $request->bearerToken();
        $token         = $request->get('token', $bearerToken);
        $expectedToken = config('services.steadfast.webhook_token') ?? config('services.steadfast.api_key');

        if (!$token || $token !== $expectedToken) {
            Log::warning('Unauthorized Steadfast Webhook attempt.', ['received' => $token]);
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }

        // 1. Identify the order by invoice (order_number)
        $invoice = $request->input('invoice');
        if (!$invoice) {
            return response()->json(['status' => 'error', 'message' => 'Invoice missing.'], 400);
        }

        $order = Order::with(['items', 'customer'])
            ->where('order_number', $invoice)
            ->first();

        if (!$order) {
            $consignmentRecord = DB::table('delivery_consignments')
                ->where('consignment_id', $request->input('consignment_id'))
                ->orWhere('tracking_code', $request->input('tracking_code'))
                ->orWhere('api_response', 'like', '%"invoice": "' . $invoice . '"%')
                ->first();

            if ($consignmentRecord) {
                $order = Order::with(['items', 'customer'])->find($consignmentRecord->order_id);
            }
        }

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found.'], 404);
        }

        $notificationType = $request->input('notification_type');
        $steadfastStatus  = strtolower($request->input('status', ''));

        // 2. Map Steadfast status to local order_status_id (BuzzBangladesh statuses)
        $statusMap = [
            'pending'   => 1, // Pending
            'delivered' => 5, // Delivered
            'returned'  => 7, // Returned
        ];

        // 3. Always update the delivery consignment record with latest payload
        $consignment = DB::table('delivery_consignments')
            ->where('order_id', $order->id)
            ->where('provider', 'steadfast')
            ->first();

        if ($consignment) {
            $consignmentUpdate = [
                'provider_status' => $steadfastStatus ?: $consignment->provider_status,
                'webhook_payload' => json_encode($request->all()),
                'updated_at'      => now(),
            ];
            if ($steadfastStatus === 'delivered') {
                $consignmentUpdate['delivered_at'] = now();
            }
            DB::table('delivery_consignments')
                ->where('id', $consignment->id)
                ->update($consignmentUpdate);
        }

        // 4. Handle tracking_update — advance status to "Shipped" (4) if not there yet
        if ($notificationType === 'tracking_update') {
            if ((int) $order->order_status_id < 4) {
                $order->update(['order_status_id' => 4]);
            }
            return response()->json(['status' => 'success', 'message' => 'Webhook received successfully.']);
        }

        // 5. Handle delivery_status notifications
        if (!isset($statusMap[$steadfastStatus])) {
            Log::info("Steadfast Webhook: Unknown status '{$steadfastStatus}' for order #{$order->id}. No action taken.");
            return response()->json(['status' => 'success', 'message' => 'Webhook received successfully.']);
        }

        $newStatusId     = $statusMap[$steadfastStatus];
        $currentStatusId = (int) $order->order_status_id;

        // Idempotency guard — skip if already in this status
        if ($currentStatusId === $newStatusId) {
            return response()->json(['status' => 'success', 'message' => 'Webhook received successfully.']);
        }

        // 6. Update status
        // For 'returned' (7), you might want to restore stock, similar to vejalnai.
        if ($newStatusId === 7 && $currentStatusId !== 7) {
            DB::transaction(function () use ($order, $newStatusId) {
                $order->update(['order_status_id' => $newStatusId]);
                
                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product_variation_id) {
                        \App\Models\ProductVariation::where('id', $item->product_variation_id)
                            ->increment('stock_quantity', $item->quantity);
                    }
                }
            });
        } else {
            $order->update(['order_status_id' => $newStatusId]);
        }

        Log::info("Steadfast Webhook: Order #{$order->id} ({$order->order_number}) status → {$steadfastStatus} (ID: {$newStatusId}).");
        return response()->json(['status' => 'success', 'message' => 'Webhook received successfully.']);
    }
}
