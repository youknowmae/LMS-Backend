<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectAuthor>
 */
class ProjectAuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->lastName() . ', ' . fake()->firstName . ' ' . Str::upper(fake()->randomLetter()). '.',
            'project_id' => fake()->numberBetween(1, 100)
        ];
    }
}
