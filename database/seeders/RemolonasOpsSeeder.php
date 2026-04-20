<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Plan;
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
    }
}
