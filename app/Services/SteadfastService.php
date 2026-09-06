<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SteadfastService
{
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.steadfast.api_key');
        $this->secretKey = config('services.steadfast.secret_key');
        $this->baseUrl = config('services.steadfast.base_url');
    }

    /**
     * Create a new order in Steadfast Courier.
     *
     * @param Order $order
     * @return array
     */
    public function createOrder(Order $order)
    {
        if (!$this->apiKey || !$this->secretKey) {
            Log::error('Steadfast API credentials missing.');
            return ['success' => false, 'message' => 'API credentials missing.'];
        }

        // Prepare data
        // COD amount: if it's a COD order, collect the total amount. 
        // Otherwise collect 0.
        $codAmount = (float) ($order->payment_method === 'cod' ? $order->total_amount : 0);

        $payload = [
            'invoice'           => $order->order_number,
            'recipient_name'    => $order->customer?->name ?? 'Customer',
            'recipient_phone'   => $order->customer?->phone ?? '',
            'recipient_address' => trim(($order->shipping_address ?? $order->customer?->address) . ($order->thana ? ', Thana: ' . $order->thana : '') . ($order->city ? ', District: ' . $order->city : ''), ', '),
            'cod_amount'        => $codAmount,
            'note'              => $order->notes ?? '',
        ];

        Log::info('Steadfast Order Creation Payload:', $payload);

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Api-Key'     => $this->apiKey,
                'Secret-Key'  => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/create_order', $payload);

            $result = $response->json();
            Log::info('Steadfast API Response:', (array) $result);

            if ($response->successful() && isset($result['status']) && $result['status'] === 200) {
                $this->recordConsignment($order, $result, $payload);
                return ['success' => true, 'message' => 'Order created successfully.', 'data' => $result];
            }

            return [
                'success' => false,
                'message' => $result['errors'] ?? $result['message'] ?? 'Failed to create order in Steadfast.',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Steadfast API Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    /**
     * Record the consignment in the database.
     */
    protected function recordConsignment(Order $order, $result, $payload)
    {
        $consignmentData = [
            'provider_status'   => 'dispatched',
            'consignment_id'    => $result['consignment']['consignment_id'] ?? null,
            'tracking_code'     => $result['consignment']['tracking_code'] ?? null,
            'delivery_type'     => 'normal',
            'is_cod'            => $payload['cod_amount'] > 0,
            'amount_to_collect' => $payload['cod_amount'],
            'recipient_name'    => $payload['recipient_name'],
            'recipient_phone'   => $payload['recipient_phone'],
            'recipient_address' => $payload['recipient_address'],
            'api_response'      => json_encode($result),
            'dispatched_at'     => now(),
            'updated_at'        => now(),
        ];

        DB::table('delivery_consignments')->updateOrInsert(
            [
                'order_id' => $order->id,
                'provider' => 'steadfast'
            ],
            array_merge($consignmentData, ['created_at' => now()])
        );
    }
}
