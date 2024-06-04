<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;
use App\Models\Material;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $accession = Str::random(15); // Generate a random string of length 5

        // Ensure uniqueness
        while (Material::where('accession', $accession)->exists()) {
            $accession = Str::random(15);
        }

        $authors = [ 
            fake()->name(),
            fake()->name(),
            fake()->name()
        ];

        $type = fake()->numberBetween(0, 2);

        if($type == 0) {
            $material = [
                'material_type' => $type,
                'accession' => $accession,
                'call_number' => Str::random(10),
                'title' => Str::title(fake()->words(3, true)), 
                'authors' => json_encode($authors),
                'location' => fake()->randomElement(['FIL', 'MED', 'COM', 'FOR']),
                'volume' => fake()->numberBetween(1, 3),
                'edition' => fake()->numberBetween(1, 3),
                'pages' => fake()->randomNumber(3),
                'acquired_date' => fake()->date(),
                'remarks' => fake()->sentence(),
                'price' => fake()->randomFloat(2, 100, 2000),
                'source_of_fund' => fake()->numberBetween(0, 2),
                'copyright' => fake()->numberBetween(1990, 2024)
            ];
        } elseif ($type == 1) {
            $material = [
                'material_type' => $type,
                'accession' => $accession,
                'periodical_type' => fake()->numberBetween(0, 2),
                'title' => Str::title(fake()->words(3, true)), 
                'authors' => json_encode($authors),
                'language' => fake()->randomElement(['english', 'tagalog']),
                'acquired_date' => fake()->date(),   
                'publisher' => fake()->company(),
                'copyright' => fake()->year(),
                'volume' => fake()->numberBetween(1, 3),
                'issue' => fake()->numberBetween(1, 5),
                'pages' => fake()->randomNumber(3),
                'remarks' => fake()->sentence(),
                'date_published' => fake()->date()      
            ];
        } elseif ($type == 2) {
            $material = [
                'material_type' => $type,
                'accession' => $accession,
                'periodical_type' => fake()->numberBetween(0, 2),
                'title' => Str::title(fake()->words(3, true)), 
                'authors' => json_encode($authors),
                'language' => fake()->randomElement(['english', 'tagalog']),
                'subject' => fake()->sentence(7, true),
                'date_published' => fake()->date(),
                'publisher' => fake()->company(),
                'volume' => fake()->numberBetween(1, 3),
                'issue' => fake()->numberBetween(1, 3),
                'pages' => fake()->randomNumber(3),
                'abstract' => fake()->sentences(2, true),
                'remarks' => fake()->sentence()
            ];
        }

        return $material;
    }
}
