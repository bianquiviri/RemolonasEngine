<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Support\Str;

class RemolonasOpsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'customer']);
    }

    public function test_customer_cannot_access_supervisor_dashboard()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($customer)
            ->getJson('/api/v1/dashboard/supervisor')
            ->assertStatus(403);
    }

    public function test_supervisor_can_access_supervisor_dashboard()
    {
        $supervisor = User::factory()->create();
        $supervisor->assignRole('supervisor');

        $this->actingAs($supervisor)
            ->getJson('/api/v1/dashboard/supervisor')
            ->assertStatus(200)
            ->assertJsonStructure(['global_stats', 'store_monitoring', 'alerts']);
    }

    public function test_operator_can_update_order_status()
    {
        $operator = User::factory()->create();
        $operator->assignRole('operator');

        $store = Store::create(['id' => (string) Str::uuid(), 'name' => 'Test Store', 'location' => 'Test Loc']);
        $plan = Plan::create(['name' => 'Test Plan', 'price' => 10, 'active' => true]);
        $sub = Subscription::create([
            'id' => (string) Str::uuid(),
            'user_id' => User::factory()->create()->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'frequency' => 'weekly',
            'next_delivery_date' => now()
        ]);

        $order = Order::create([
            'id' => (string) Str::uuid(),
            'subscription_id' => $sub->id,
            'store_id' => $store->id,
            'status' => 'pending',
            'scheduled_delivery_date' => now()
        ]);

        $this->actingAs($operator)
            ->patchJson("/api/v1/orders/{$order->id}/status", ['status' => 'picking'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'picking');
    }

    public function test_supervisor_can_manage_stores()
    {
        $supervisor = User::factory()->create();
        $supervisor->assignRole('supervisor');

        $this->actingAs($supervisor)
            ->postJson('/api/v1/stores', [
                'name' => 'New Store',
                'location' => 'Barcelona'
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('stores', ['name' => 'New Store']);
    }
}
