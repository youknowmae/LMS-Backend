<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

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
            'accession' => Str::random(5),
            'material_type' => fake()->randomElement(['journal', 'magazine', 'newspaper']),
            'title' => fake()->words(3, true),
            'authors' => fake()->name(),
            'language' => fake()->randomElement(['english', 'tagalog']),
            'receive_date' => fake()->date(),   
            'publisher' => fake()->company(),
            'copyright' => fake()->year(),
            'volume' => fake()->numberBetween(1, 3),
            'issue' => fake()->numberBetween(1, 5),
            'pages' => fake()->randomNumber(3),
            'remarks' => fake()->sentence(),
            'date_published' => fake()->date()        
        ];
    }
}
