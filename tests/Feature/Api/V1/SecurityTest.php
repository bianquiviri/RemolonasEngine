<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'operator']);
    }

    public function test_login_rate_limiting_after_5_attempts()
    {
        $user = User::factory()->create([
            'email' => 'victim@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Intentar 5 veces con password incorrecto
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/login', [
                'email' => 'victim@example.com',
                'password' => 'wrong-password',
            ])->assertStatus(422);
        }

        // El 6º intento debe ser bloqueado por Rate Limiting (429 Too Many Attempts)
        $this->postJson('/api/v1/login', [
            'email' => 'victim@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    public function test_unauthorized_role_access_to_supervisor_endpoints()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        // Un cliente no debe poder acceder al dashboard de supervisor
        $this->actingAs($customer)
            ->getJson('/api/v1/dashboard/supervisor')
            ->assertStatus(403);

        // Un cliente no debe poder ver la lista de tiendas
        $this->actingAs($customer)
            ->getJson('/api/v1/stores')
            ->assertStatus(403);
    }

    public function test_operator_isolation_to_their_store_only()
    {
        $storeA = Store::factory()->create(['name' => 'Store A']);
        $storeB = Store::factory()->create(['name' => 'Store B']);

        $operator = User::factory()->create(['store_id' => $storeA->id]);
        $operator->assignRole('operator');

        // Crear pedidos para ambas tiendas
        Order::factory()->create(['store_id' => $storeA->id, 'status' => 'pending']);
        Order::factory()->create(['store_id' => $storeB->id, 'status' => 'pending']);

        // El operador solo debe ver pedidos de la Tienda A
        $response = $this->actingAs($operator)
            ->getJson('/api/v1/dashboard/operator')
            ->assertStatus(200);

        $this->assertEquals(1, $response->json('pending_orders_count'));
        $this->assertEquals('Store A', $response->json('store_info.name'));
    }
}
