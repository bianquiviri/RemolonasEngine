<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Support\Str;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'customer']);
    }

    public function test_customer_dashboard_returns_correct_stats()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');
        
        $plan = Plan::create(['name' => 'Basic', 'price' => 10, 'active' => true]);
        Subscription::create([
            'id' => (string) Str::uuid(),
            'user_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'frequency' => 'weekly',
            'next_delivery_date' => now()
        ]);

        $this->actingAs($customer)
            ->getJson('/api/v1/dashboard/customer')
            ->assertStatus(200)
            ->assertJsonPath('active_subscriptions', 1);
    }

    public function test_operator_dashboard_requires_operator_role()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($customer)
            ->getJson('/api/v1/dashboard/operator')
            ->assertStatus(403);
    }

    public function test_supervisor_dashboard_requires_supervisor_role()
    {
        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $this->actingAs($operator)
            ->getJson('/api/v1/dashboard/supervisor')
            ->assertStatus(403);
    }
}
