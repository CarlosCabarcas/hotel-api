<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'nit' => fake()->unique()->numerify('########'),
            'total_rooms' => fake()->numberBetween(10, 100)
        ];
    }
}
