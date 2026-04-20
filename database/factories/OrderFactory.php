<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'subscription_id' => Subscription::factory(),
            'store_id' => Store::factory(),
            'status' => 'pending',
            'scheduled_delivery_date' => now()->addDays(2),
        ];
    }
}
