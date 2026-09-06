<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\SteadfastService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchSteadfastOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(SteadfastService $steadfastService)
    {
        Log::info("DispatchSteadfastOrder Job: Processing Order #{$this->order->id}");

        $result = $steadfastService->createOrder($this->order);

        if (!$result['success']) {
            Log::error("DispatchSteadfastOrder Job Failed for Order #{$this->order->id}: " . $result['message']);
            
            // Fail the job so it can be retried if it's a network/temp error
            if (isset($result['data']) && ($result['data']['status'] ?? 0) >= 500) {
                 throw new \Exception("Steadfast API Server Error: " . $result['message']);
            }
        } else {
            Log::info("DispatchSteadfastOrder Job SUCCESS for Order #{$this->order->id}");
        }
    }
}
