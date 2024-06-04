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
        DB::table('programs')->insert([

            // CCS
            [
                'department_short' => 'CCS',
                'department_full' => 'College of Computer Studies',
                'program_short' => 'BSIT',
                'program_full' => 'Bachelor of Science in Information Technology',
                'category' => 'Capstone'
            ],
            [
                'department_short' => 'CCS',
                'department_full' => 'College of Computer Studies',
                'program_short' => 'BSCS',
                'program_full' => 'Bachelor of Science in Computer Science',
                'category' => 'Thesis'
            ],
            [
                'department_short' => 'CCS',
                'department_full' => 'College of Computer Studies',
                'program_short' => 'BSEMC',
                'program_full' => 'Bachelor of Science in Entertainment and Multimedia Computing',
                'category' => 'Thesis'
            ],
            [
                'department_short' => 'CCS',
                'department_full' => 'College of Computer Studies',
                'program_short' => 'ACT',
                'program_full' => 'Associate in Computer Technology',
                'category' => 'Thesis'
            ],

            // CBA
            [
                'department_short' => 'CBA',
                'department_full' => 'College of Business and Accountancy',
                'program_short' => 'BSA',
                'program_full' => 'Bachelor of Science in Accountancy',
                'category' => 'Research'
            ],
            [
                'department_short' => 'CBA',
                'department_full' => 'College of Business and Accountancy',
                'program_short' => 'BSCA',
                'program_full' => 'Bachelor of Science in Customs Administration',
                'category' => 'Research'
            ],
            [
                'department_short' => 'CBA',
                'department_full' => 'College of Business and Accountancy',
                'program_short' => 'BSBA-FM',
                'program_full' => 'Bachelor of Science in Business Administration - Major in Financial Management',
                'category' => 'Research'
            ],
            [
                'department_short' => 'CBA',
                'department_full' => 'College of Business and Accountancy',
                'program_short' => 'BSBA-MKT',
                'program_full' => 'Bachelor of Science in Business Administration - Major in Marketing Management',
                'category' => 'Research'
            ],
            [
                'department_short' => 'CBA',
                'department_full' => 'College of Business and Accountancy',
                'program_short' => 'BSBA-HRM',
                'program_full' => 'Bachelor of Science in Business Administration - Major in Human Resource Management',
                'category' => 'Research'
            ],

            // CAHS
            [
                'department_short' => 'CAHS',
                'department_full' => 'College of Allied Health Studies',
                'program_short' => 'BSN',
                'program_full' => 'Bachelor of Science in Nursing',
                'category' => 'Research'
            ],
            [
                'department_short' => 'CAHS',
                'department_full' => 'College of Allied Health Studies',
                'program_short' => 'BSM',
                'program_full' => 'Bachelor of Science in Midwifery',
                'category' => 'Research'
            ],

            // CEAS
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BACOMM',
                'program_full' => 'Bachelor of Arts in Communication',
                'category' => 'Thesis'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BEED',
                'program_full' => 'Bachelor of Elementary Education',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BPED',
                'program_full' => 'Bachelor of Physical Education',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BCAED',
                'program_full' => 'Bachelor of Cultural and Arts Education',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BECED',
                'program_full' => 'Bachelor of Early Childhood Education',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BSED-ENG',
                'program_full' => 'Bachelor of Secondary Education - Major in English',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BSED-FIL',
                'program_full' => 'Bachelor of Secondary Education - Major in Filipino',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BSED-MATH',
                'program_full' => 'Bachelor of Secondary Education - Major in Math',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BSED-SCI',
                'program_full' => 'Bachelor of Secondary Education - Major in Science',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'department_short' => 'CEAS',
                'department_full' => 'College of Education, Arts and Sciences',
                'program_short' => 'BSED-SOC',
                'program_full' => 'Bachelor of Secondary Education - Major in Social Studies',
                'category' => 'Classroom Based Action Research'
            ],

            // CHTM
            [
                'department_short' => 'CHTM',
                'department_full' => 'College of Hospitality and Tourism Management',
                'program_short' => 'BSHM',
                'program_full' => 'Bachelor of Science in Hospitality Management',
                'category' => 'Feasibility Study'
            ],
            [
                'department_short' => 'CHTM',
                'department_full' => 'College of Hospitality and Tourism Management',
                'program_short' => 'BSTM',
                'program_full' => 'Bachelor of Science in Tourism Management',
                'category' => 'Thesis'
            ],
        ]);
    }
}
