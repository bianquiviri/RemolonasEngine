<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create main test user
        User::factory()->create([
            'name' => 'Admin Remolonas',
            'email' => 'admin@remolonas.com',
            'password' => bcrypt('password'),
        ]);

        // Create random users
        User::factory(10)->create();

        $this->call([
            PlanSeeder::class,
            ProductSeeder::class,
            SubscriptionSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
