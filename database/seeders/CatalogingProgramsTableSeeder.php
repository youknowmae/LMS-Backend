<?php

use Illuminate\Database\Seeder;
use App\Models\Program;

class CatalogingProgramsTableSeeder extends Seeder
{
    public function run()
    {
        $programs = [
            ['program' => 'BSIT', 'department_id' => 1, 'category' => 'Capstone'],
            ['program' => 'BSCS', 'department_id' => 1, 'category' => 'Thesis'],
            ['program' => 'BSEMC', 'department_id' => 1, 'category' => 'Thesis'],
            ['program' => 'ACT', 'department_id' => 1, 'category' => 'Thesis'],
            ['program' => 'BSA', 'department_id' => 5, 'category' => 'Research'],
            ['program' => 'BSCA', 'department_id' => 5, 'category' => 'Research'],
            ['program' => 'BSBA-FM', 'department_id' => 5, 'category' => 'Research'],
            ['program' => 'BSBA-MKT', 'department_id' => 5, 'category' => 'Research'],
            ['program' => 'BSBA-HRM', 'department_id' => 5, 'category' => 'Research'],
            ['program' => 'BSN', 'department_id' => 2, 'category' => 'Research'],
            ['program' => 'BSM', 'department_id' => 2, 'category' => 'Research'],
            ['program' => 'BACOMM', 'department_id' => 3, 'category' => 'Thesis'],
            ['program' => 'BEED', 'department_id' => 3, 'category' => 'Classroom Based Action Research'],
            ['program' => 'BPED', 'department_id' => 3, 'category' => 'Classroom Based Action Research'],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
