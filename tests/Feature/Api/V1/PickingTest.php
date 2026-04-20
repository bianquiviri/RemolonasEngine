<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PickingTest extends TestCase
{
    use RefreshDatabase;

    protected $operator;
    protected $order;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'operator']);
        
        $store = Store::factory()->create();
        $this->operator = User::factory()->create(['store_id' => $store->id]);
        $this->operator->assignRole('operator');

        $this->product = Product::factory()->create(['barcode' => '123456789']);
        
        $this->order = Order::factory()->create([
            'store_id' => $store->id,
            'status' => 'pending'
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);
    }

    public function test_operator_can_scan_valid_barcode()
    {
        $response = $this->actingAs($this->operator)
            ->postJson('/api/v1/picking/scan', [
                'order_id' => $this->order->id,
                'barcode' => '123456789'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Producto recogido con éxito')
            ->assertJsonPath('item.is_picked', true);

        $this->order->refresh();
        $this->assertEquals('picking', $this->order->status);
    }

    public function test_operator_cannot_scan_product_not_in_order()
    {
        $otherProduct = Product::factory()->create(['barcode' => '999999999']);

        $response = $this->actingAs($this->operator)
            ->postJson('/api/v1/picking/scan', [
                'order_id' => $this->order->id,
                'barcode' => '999999999'
            ]);

        $response->assertStatus(400)
            ->assertJsonPath('message', 'Este producto no pertenece al pedido o ya ha sido recogido');
    }

    public function test_complete_picking_fails_if_items_missing()
    {
        $response = $this->actingAs($this->operator)
            ->postJson("/api/v1/picking/complete/{$this->order->id}");

        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Faltan 1 items por recoger']);
    }

    public function test_complete_picking_success_when_all_items_picked()
    {
        // Pick the item first
        OrderItem::where('order_id', $this->order->id)->update(['is_picked' => true]);

        $response = $this->actingAs($this->operator)
            ->postJson("/api/v1/picking/complete/{$this->order->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Picking completado. Pedido listo para despacho.');

        $this->order->refresh();
        $this->assertEquals('ready_for_dispatch', $this->order->status);
    }
}
