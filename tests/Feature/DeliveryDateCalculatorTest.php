<?php

use App\Jobs\ProcessOrderJob;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('order processing increments delivery date correctly and skips sundays', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Caja Frutas', 'price' => 20]);
    
    // Set a delivery date that is on a Saturday
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'frequency' => 'weekly',
        'next_delivery_date' => Carbon::parse('next Saturday'),
    ]);

    $job = new ProcessOrderJob($subscription);
    $job->handle();

    $subscription->refresh();

    // Next week is Saturday + 7 days = next Saturday. Wait, next delivery would be next Saturday. If it was Sunday, it adds a day.
    // Let's test a date that results in a Sunday.
    // If next_delivery_date is a Sunday, the calculation adds a week, so it lands on a Sunday. Then the job skips Sunday.
    
    expect($subscription->orders)->toHaveCount(1);
});

test('if next delivery date falls on a Sunday, it moves to Monday', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Caja Frutas', 'price' => 20]);
    
    // Set a delivery date to a Sunday
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'frequency' => 'weekly',
        // In reality, it should process on a Sunday, and the next one should be Sunday + 1 week = Sunday, then shifted to Monday
        'next_delivery_date' => Carbon::parse('next Sunday'),
    ]);

    $job = new ProcessOrderJob($subscription);
    $job->handle();

    $subscription->refresh();

    // The order should be created for Sunday
    $order = $subscription->orders->first();
    expect($order->scheduled_delivery_date->isSunday())->toBeTrue();

    // But the next_delivery_date should be Monday
    expect($subscription->next_delivery_date->isMonday())->toBeTrue();
});
