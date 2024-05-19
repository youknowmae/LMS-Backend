<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

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
            'accession' => Str::random(5),
            'title' => fake()->words(3, true),
            'date_published' => fake()->date(), 
            'language' => fake()->randomElement(['FIL', 'FOR']),
            'abstract' => fake()->sentence(20, true),
        ];
    }
}
