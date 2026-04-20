<?php

use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessOrderJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('generates weekly orders dispatches jobs for active subscriptions due within 7 days', function () {
    Queue::fake();

    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Caja Frutas', 'price' => 20]);
    
    // Due today
    Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'next_delivery_date' => now(),
    ]);

    // Due in 10 days (should be ignored)
    Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'next_delivery_date' => now()->addDays(10),
    ]);

    // Paused (should be ignored)
    Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'paused',
        'next_delivery_date' => now(),
    ]);

    $this->artisan('logistics:generate-weekly-orders')
        ->assertExitCode(0);

    Queue::assertPushed(ProcessOrderJob::class, 1);
});
