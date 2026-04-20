<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Tomate Ecológico', 'available_quantity' => 100, 'image_url' => 'https://images.unsplash.com/photo-1518977676601-b53f02ac10dd?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Zanahoria de la Sierra', 'available_quantity' => 200, 'image_url' => 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Manzana Reineta', 'available_quantity' => 150, 'image_url' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6bcd6?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Lechuga Batavia', 'available_quantity' => 80, 'image_url' => 'https://images.unsplash.com/photo-1556801712-76c820ac673d?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Cebolla Morada', 'available_quantity' => 120, 'image_url' => 'https://images.unsplash.com/photo-1620574387735-3624d75b2dbc?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Calabacín Verde', 'available_quantity' => 90, 'image_url' => 'https://images.unsplash.com/photo-1503146234394-adc303fd6238?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Pimiento Rojo', 'available_quantity' => 70, 'image_url' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&q=80&w=400'],
            ['name' => 'Patata Monalisa', 'available_quantity' => 300, 'image_url' => 'https://images.unsplash.com/photo-1518977676601-b53f02ac10dd?auto=format&fit=crop&q=80&w=400'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
