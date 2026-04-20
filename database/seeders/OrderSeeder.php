<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {
            // Past delivered order
            Order::create([
                'id' => (string) Str::uuid(),
                'subscription_id' => $subscription->id,
                'status' => 'delivered',
                'scheduled_delivery_date' => now()->subDays(7),
                'created_at' => now()->subDays(10),
            ]);

            // Current processing order
            Order::create([
                'id' => (string) Str::uuid(),
                'subscription_id' => $subscription->id,
                'status' => 'processing',
                'scheduled_delivery_date' => $subscription->next_delivery_date,
                'created_at' => now(),
            ]);
        }
    }
}
