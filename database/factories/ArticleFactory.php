<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $authors = [ 
            fake()->name(),
            fake()->name(),
            fake()->name()
        ];

        return [
            'accession' => Str::random(5),
            'material_type' => fake()->randomElement(['journal', 'magazine', 'newspaper']),
            'title' => Str::title(fake()->words(3, true)), 
            'authors' => json_encode($authors),
            'language' => fake()->randomElement(['english', 'tagalog']),
            'subject' => fake()->sentences(2, true),
            'date_published' => fake()->date(),
            'publisher' => fake()->company(),
            'volume' => fake()->numberBetween(1, 3),
            'issue' => fake()->numberBetween(1, 3),
            'pages' => fake()->randomNumber(3),
            'abstract' => fake()->sentences(2, true),
            'remarks' => fake()->sentence()
        ];
    }
}