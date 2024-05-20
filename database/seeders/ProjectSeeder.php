<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /* CCS */
        Project::factory()->count(10)->create([
            'program_id' => 1,
            'category' => 'Capstone',
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);

        Project::factory()->count(10)->create([
            'program_id' => 2,
            'category' => 'Thesis',
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);

        Project::factory()->count(10)->create([
            'program_id' => 3,
            'category' => 'Thesis',
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);

        /* CBA */
        Project::factory()->count(20)->create([
            'program_id' => fn() => fake()->numberBetween(5, 9),
            'category' => fn() => fake()->randomElement(['Research', 'Feasibility Study']),
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);

        /* CAHS */
        Project::factory()->count(20)->create([
            'program_id' => fn() => fake()->numberBetween(10, 11),
            'category' => 'Research',
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);

        /* CEAS */
        Project::factory()->count(40)->create([
            'program_id' => fn() => fake()->numberBetween(13, 21),
            'category' => 'Classroom Based Action Research',
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);

        /* CHTM */
        Project::factory()->count(10)->create([
            'program_id' => fn() => fake()->numberBetween(22, 23),
            'category' => fn() => fake()->randomElement(['Research', 'Feasibility Study']),
            'keywords' => '["technology", "magic", "chakra", "haki"]'
        ]);
    }
}
