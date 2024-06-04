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
        Project::factory()->count(30)->create([
            'program' => 'BSIT',
            'category' => 'Capstone'
        ]);

        Project::factory()->count(30)->create([
            'program' => 'BSCS',
            'category' => 'Thesis'
        ]);

        Project::factory()->count(30)->create([
            'program' => 'BSEMC',
            'category' => 'Research'
        ]);

        /* CBA */
        Project::factory()->count(500)->create([
            'program' => fn() => fake()->randomElement(['BSA', 'BSCA', 'BSBA-FM', 'BSBA-MKT', 'BSBA-HRM']),
            'category' => fn() => fake()->randomElement(['Research', 'Feasibility Study'])
        ]);

        /* CAHS */
        Project::factory()->count(200)->create([
            'program' => fn() => fake()->randomElement(['BSN', 'BSM']),
            'category' => 'Research'
        ]);

        /* CEAS */
        Project::factory()->count(1000)->create([
            'program' => fn() => fake()->randomElement(['BSED-SOC', 'BSED-SCI', 'BSED-MATH', 
                                'BSED-FIL', 'BSED-ENG', 'BECED', 'BCAED', 'BPED', 'BEED', 'BACOMM']),
            'category' => 'Classroom Based Action Research'
        ]);

        /* CHTM */
        Project::factory()->count(300)->create([
            'program' => fn() => fake()->randomElement(['BSHM', 'BSTM']),
            'category' => fn() => fake()->randomElement(['Thesis', 'Feasibility Study'])
        ]);
    }
}
