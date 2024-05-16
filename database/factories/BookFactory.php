<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'call_number' => Str::random(10),
            'title' => fake()->words(3, true),
            //'author' => fake()->name(),
            'location_id' => fake()->numberBetween(1, 4),
            'volume' => fake()->numberBetween(1, 3),
            'edition' => fake()->numberBetween(1, 3),
            'pages' => fake()->randomNumber(3),
            'acquired_date' => fake()->date(),
            'remarks' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 2000),
            'source_of_fund' => fake()->randomElement(['Donated', 'Purchased', 'Replacement']),
            'copyright' => fake()->numberBetween(1990, 2024)
        ];
    }
}
