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
            'category' => fake()->randomElement(['thesis', 'dissertation', 'capstone', 'feasibility study']),
            'title' => fake()->words(3, true),
            'author' => fake()->name(),
            'department' => fake()->randomElement(['CCS', 'CBA', 'CEAS', 'CHTM', 'CAHS']),
            'course' => fake()->randomElement(['BSIT', 'BSCS',  'BSEMC', 'ACT', 'BSA', 'BSCA', 'BSBA-FM', 'BSBA-HRM', 'BSBA-MKT', 'BSN', 'BSM', 'GM', 'BSHM', 'BSTM', 'BEEd', 'BECEd', 'BSEd-E', 'BSEd-FIL', 
        'BSEd-M', 'BSEd-SCI', 'BSEd-SOC', 'BPEd', 'BCAEd', 'BACOM', 'TCP']),
            'image_location' => fake()->filePath(), 
            'date_published' => fake()->date(), 
            'language' => fake()->randomElement(['FIL', 'FOR']),
            'abstract' => fake()->sentence(20, true),
        ];
    }
}
