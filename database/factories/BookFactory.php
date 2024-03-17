<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'title' => fake()->words(3, true),
            'author' => fake()->name(),
            'image' => fake()->filePath(),
            'language' => fake()->randomElement(['FIL', 'FOR']),
            'category_id' => fake()->numberBetween(1, 10),
            'publisher' => fake()->company(),
            'copyright' => fake()->sentences(3, true),
            'volume' => fake()->numberBetween(1, 3),
            'issue' => fake()->numberBetween(1, 3),
            'pages' => fake()->randomNumber(3),
            'blurb' => fake()->sentence(),
            'published_date' => fake()->date()
        ];
    }
}
