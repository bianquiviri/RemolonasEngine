<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->word() . ' Box',
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 20, 100),
            'frequency' => 'weekly',
        ];
    }
}
