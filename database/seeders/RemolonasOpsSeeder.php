<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RemolonasOpsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'supervisor']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // 2. Create Supervisor
        $admin = User::firstOrCreate(
            ['email' => 'admin@remolonas.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Admin Supervisor',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Create Stores
        $store1 = Store::firstOrCreate(
            ['name' => 'Tienda Centro'],
            ['id' => (string) Str::uuid(), 'location' => 'Calle Mayor 1, Madrid', 'active' => true]
        );

        $store2 = Store::firstOrCreate(
            ['name' => 'Tienda Norte'],
            ['id' => (string) Str::uuid(), 'location' => 'Paseo Castellana 200, Madrid', 'active' => true]
        );

        // 4. Create Operators
        $operator = User::firstOrCreate(
            ['email' => 'operator@remolonas.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Juan Picker',
                'password' => bcrypt('password'),
                'store_id' => $store1->id,
            ]
        );
        $operator->assignRole($operatorRole);

        // 5. Create Sample Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@remolonas.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Maria Cliente',
                'password' => bcrypt('password'),
            ]
        );
        $customer->assignRole($customerRole);

        // 6. Create Products
        $product1 = Product::firstOrCreate(
            ['name' => 'Tomates Bio'],
            ['id' => (string) Str::uuid(), 'barcode' => '8412345678901', 'available_quantity' => 100]
        );

        $product2 = Product::firstOrCreate(
            ['name' => 'Lechuga Romana'],
            ['id' => (string) Str::uuid(), 'barcode' => '8412345678902', 'available_quantity' => 100]
        );

        // 7. Create Sample Plan & Subscription
        $plan = Plan::firstOrCreate(
            ['name' => 'Caja Huerto Familiar'],
            ['id' => (string) Str::uuid(), 'price' => 29.99, 'description' => 'Ideal para familias de 4 personas']
        );

        $subscription = \App\Models\Subscription::firstOrCreate(
            ['user_id' => $customer->id],
            [
                'id' => (string) Str::uuid(),
                'plan_id' => $plan->id,
                'status' => 'active',
                'delivery_day' => 'Friday'
            ]
        );

        // 8. Create Order with Items
        $order = Order::firstOrCreate(
            ['subscription_id' => $subscription->id, 'status' => 'pending'],
            [
                'id' => (string) Str::uuid(),
                'store_id' => $store1->id,
                'scheduled_delivery_date' => now()->addDays(2)
            ]
        );

        OrderItem::firstOrCreate([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2
        ]);

        OrderItem::firstOrCreate([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1
        ]);
    }
}
