<?php

namespace Tests\Feature\Api\V1;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_plans_publicly()
    {
        Plan::create(['name' => 'Plan A', 'price' => 10, 'active' => true]);

        $response = $this->getJson('/api/v1/plans');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Plan A');
    }

    public function test_can_create_subscription_when_authenticated()
    {
        $user = User::factory()->create();
        $plan = Plan::create(['name' => 'Plan A', 'price' => 10, 'active' => true]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/subscriptions', [
            'plan_id' => $plan->id,
            'frequency' => 'weekly',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'active');
            
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);
    }

    public function test_cannot_view_others_subscriptions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $plan = Plan::create(['name' => 'Plan A', 'price' => 10, 'active' => true]);
        
        $subscription = Subscription::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user1->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'next_delivery_date' => now(),
        ]);

        Sanctum::actingAs($user2);

        $response = $this->getJson("/api/v1/subscriptions/{$subscription->id}");

        $response->assertStatus(403);
    }
}
