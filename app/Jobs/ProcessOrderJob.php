<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscription;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [10, 30, 60];

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        Log::info("Processing order for subscription {$this->subscription->id}");

        DB::transaction(function () {
            // Check if order already exists for this scheduled date to prevent duplicates
            $existingOrder = Order::where('subscription_id', $this->subscription->id)
                ->where('scheduled_delivery_date', $this->subscription->next_delivery_date)
                ->lockForUpdate()
                ->first();

            if ($existingOrder) {
                Log::warning("Order already exists for subscription {$this->subscription->id} on {$this->subscription->next_delivery_date}");
                return;
            }

            // Create Order
            $order = Order::create([
                'subscription_id' => $this->subscription->id,
                'status' => 'pending',
                'scheduled_delivery_date' => $this->subscription->next_delivery_date,
            ]);

            // Calculate next delivery date based on frequency
            $nextDelivery = match ($this->subscription->frequency) {
                'weekly' => $this->subscription->next_delivery_date->addWeek(),
                'monthly' => $this->subscription->next_delivery_date->addMonth(),
                default => $this->subscription->next_delivery_date->addWeek(),
            };

            // Skip Sundays (DeliveryDateCalculator logic in-line for now)
            if ($nextDelivery->isSunday()) {
                $nextDelivery->addDay();
            }

            $this->subscription->update([
                'next_delivery_date' => $nextDelivery
            ]);

            Log::info("Order {$order->id} created successfully.");
        }, 5); // 5 deadlocks retries
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Failed to process order for subscription {$this->subscription->id}: {$exception->getMessage()}");
    }
}
