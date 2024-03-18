<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->numberBetween(1, 4), 
            'language' => fake()->randomElement(['FIL', 'FOR']),
            'date_published' => fake()->date(),
            'title' => fake()->words(3, true),
            'author' => fake()->name(),
            'course_id' => fake()->numberBetween(1, 4),   
            'abstract' => fake()->sentence(20, true),
        ];
    }
}
