<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periodical>
 */
class PeriodicalFactory extends Factory
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
            'category_id' => fake()->numberBetween(1, 10),
            'material_type' => fake()->randomElement(['journal', 'magazine', 'newspaper']),
            'language' => fake()->randomElement(['FIL', 'FOR']),
            'image' => fake()->filePath(),
            'date_published' => fake()->date(),
            'publisher' => fake()->company(),
            'copyright' => fake()->sentences(3, true),
            'volume' => fake()->numberBetween(1, 3),
            'issue' => fake()->numberBetween(1, 3),
            'pages' => fake()->randomNumber(3),
            'blurb' => fake()->sentence(),
        ];
    }
}
