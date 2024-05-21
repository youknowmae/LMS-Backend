<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'department' => 'CCS',
                'full_department' => 'College of Computer Studies'
            ],

            [
                'department' => 'CAHS',
                'full_department' => 'College of Allied Health Studies'
            ],

            [
                'department' => 'CEAS',
                'full_department' => 'College of Education, Arts and Sciences'
            ],

            [
                'department' => 'CHTM',
                'full_department' => 'College of Hospitality and Tourism Management'
            ],

            [
                'department' => 'CBA',
                'full_department' => 'College of Business and Accountancy'
            ],

        ]);

        DB::table('programs')->insert([

            // CCS
            [
                'program' => 'BSIT',
                'full_program' => 'Bachelor of Science in Information Technology',
                'department_id' => 1,
                'category' => 'Capstone'
            ],
            [
                'program' => 'BSCS',
                'full_program' => 'Bachelor of Science in Computer Science',
                'department_id' => 1,
                'category' => 'Thesis'
            ],
            [
                'program' => 'BSEMC',
                'full_program' => 'Bachelor of Science in Entertainment and Multimedia Computing',
                'department_id' => 1,
                'category' => 'Thesis'
            ],
            [
                'program' => 'ACT',
                'full_program' => 'Associate in Computer Technology',
                'department_id' => 1,
                'category' => 'Thesis'
            ],

            // CBA
            [
                'program' => 'BSA',
                'full_program' => 'Bachelor of Science in Accountancy',
                'department_id' => 5,
                'category' => 'Research'
            ],
            [
                'program' => 'BSCA',
                'full_program' => 'Bachelor of Science in Customs Administration',
                'department_id' => 5,
                'category' => 'Research'
            ],
            [
                'program' => 'BSBA-FM',
                'full_program' => 'Bachelor of Science in Business Administration - Major in Financial Management',
                'department_id' => 5,
                'category' => 'Research'
            ],
            [
                'program' => 'BSBA-MKT',
                'full_program' => 'Bachelor of Science in Business Administration - Major in Marketing Management',
                'department_id' => 5,
                'category' => 'Research'
            ],
            [
                'program' => 'BSBA-HRM',
                'full_program' => 'Bachelor of Science in Business Administration - Major in Human Resource Management',
                'department_id' => 5,
                'category' => 'Research'
            ],

            // CAHS
            [
                'program' => 'BSN',
                'full_program' => 'Bachelor of Science in Nursing',
                'department_id' => 2,
                'category' => 'Research'
            ],
            [
                'program' => 'BSM',
                'full_program' => 'Bachelor of Science in Midwifery',
                'department_id' => 2,
                'category' => 'Research'
            ],

            // BSED
            [
                'program' => 'BACOMM',
                'full_program' => 'Bachelor of Arts in Communication',
                'department_id' => 3,
                'category' => 'Thesis'
            ],
            [
                'program' => 'BEED',
                'full_program' => 'Bachelor of Elementary Education',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BPED',
                'full_program' => 'Bachelor of Physical Education',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BCAED',
                'full_program' => 'Bachelor of Cultural and Arts Education',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BECED',
                'full_program' => 'Bachelor of Early Childhood Education',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-ENG',
                'full_program' => 'Bachelor of Secondary Education - Major in English',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-FIL',
                'full_program' => 'Bachelor of Secondary Education - Major in Filipino',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-MATH',
                'full_program' => 'Bachelor of Secondary Education - Major in Math',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-SCI',
                'full_program' => 'Bachelor of Secondary Education - Major in Science',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-SOC',
                'full_program' => 'Bachelor of Secondary Education - Major in Social Studies',
                'department_id' => 3,
                'category' => 'Classroom Based Action Research'
            ],

            // CHTM
            [
                'program' => 'BSHM',
                'full_program' => 'Bachelor of Science in Hospitality Management',
                'department_id' => 4,
                'category' => 'Research'
            ],
            [
                'program' => 'BSTM',
                'full_program' => 'Bachelor of Science in Tourism Management',
                'department_id' => 4,
                'category' => 'Research'
            ],
        ]);
    }
}
