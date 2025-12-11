<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->word(),
            'price'       => rand(1, 1000),
            'category_id' => rand(1, 5),
            'in_stock'    => rand(0, 1),
            'rating'      => rand(10, 50) / 10,
        ];
    }
}
