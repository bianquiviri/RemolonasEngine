<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Caja Esencial',
                'price' => 35.00,
                'active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=800', // Fresh veg in a box
            ],
            [
                'name' => 'Caja Familiar',
                'price' => 55.00,
                'active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1590779033100-9f60705a2f3b?auto=format&fit=crop&q=80&w=800', // Large variety veg
            ],
            [
                'name' => 'Caja de Temporada',
                'price' => 45.00,
                'active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1610348725531-843dff563e2c?auto=format&fit=crop&q=80&w=800', // Seasonal fruit/veg
            ],
            [
                'name' => 'Caja Personalizada',
                'price' => 40.00,
                'active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&q=80&w=800', // Farmer market style
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
