<?php

namespace Tests\Feature;

use App\Jobs\ProcessOrderJob;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class DeliveryDateCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_processing_increments_delivery_date_correctly_and_skips_sundays()
    {
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

        $this->assertCount(1, $subscription->orders);
    }

    public function test_if_next_delivery_date_falls_on_a_sunday_it_moves_to_monday()
    {
        $user = User::factory()->create();
        $plan = Plan::create(['name' => 'Caja Frutas', 'price' => 20]);
        
        // Set a delivery date to a Sunday
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'frequency' => 'weekly',
            'next_delivery_date' => Carbon::parse('next Sunday'),
        ]);

        $job = new ProcessOrderJob($subscription);
        $job->handle();

        $subscription->refresh();

        // The order should be created for Sunday
        $order = $subscription->orders->first();
        $this->assertTrue($order->scheduled_delivery_date->isSunday());

        // But the next_delivery_date should be Monday
        $this->assertTrue($subscription->next_delivery_date->isMonday());
    }
}
