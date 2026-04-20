<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $plans = Plan::all();

        foreach ($users as $user) {
            $plan = $plans->random();
            Subscription::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'next_delivery_date' => now()->addDays(rand(1, 7)),
                'frequency' => rand(0, 1) ? 'weekly' : 'monthly',
            ]);
        }
    }
}
