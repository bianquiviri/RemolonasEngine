<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->word(),
            'barcode' => (string) $this->faker->unique()->randomNumber(8, true),
            'available_quantity' => $this->faker->numberBetween(10, 100),
        ];
    }
}
