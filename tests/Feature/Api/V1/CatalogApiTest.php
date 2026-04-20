<?php

namespace Tests\Feature\Api\V1;

use App\Models\Plan;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_active_plans()
    {
        Plan::create(['name' => 'Active Plan', 'price' => 10, 'active' => true]);
        Plan::create(['name' => 'Inactive Plan', 'price' => 10, 'active' => false]);

        $this->getJson('/api/v1/plans')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Active Plan');
    }

    public function test_can_list_products()
    {
        Product::create(['name' => 'Carrot', 'available_quantity' => 100]);
        Product::create(['name' => 'Potato', 'available_quantity' => 50]);

        $this->getJson('/api/v1/products')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_show_plan_details()
    {
        $plan = Plan::create(['name' => 'Specific Plan', 'price' => 15, 'active' => true]);

        $this->getJson("/api/v1/plans/{$plan->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Specific Plan');
    }
}
