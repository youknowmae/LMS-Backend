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
                'program' => 'BSIT',
                'department' => 'CCS',
                'category' => 'Capstone'
            ],
            [
                'program' => 'BSCS',
                'department' => 'CCS',
                'category' => 'Thesis'
            ],
            [
                'program' => 'BSEMC',
                'department' => 'CCS',
                'category' => 'Thesis'
            ],
            [
                'program' => 'ACT',
                'department' => 'CCS',
                'category' => 'Thesis'
            ],

            // CBA
            [
                'program' => 'BSA',
                'department' => 'CBA',
                'category' => 'Research'
            ],
            [
                'program' => 'BSCA',
                'department' => 'CBA',
                'category' => 'Research'
            ],
            [
                'program' => 'BSBA-FM',
                'department' => 'CBA',
                'category' => 'Research'
            ],
            [
                'program' => 'BSBA-MKT',
                'department' => 'CBA',
                'category' => 'Research'
            ],
            [
                'program' => 'BSBA-HRM',
                'department' => 'CBA',
                'category' => 'Research'
            ],

            // CAHS
            [
                'program' => 'BSN',
                'department' => 'CAHS',
                'category' => 'Research'
            ],
            [
                'program' => 'BSM',
                'department' => 'CAHS',
                'category' => 'Research'
            ],

            // BSED
            [
                'program' => 'BACOMM',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BEED',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BPED',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BCAED',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BECED',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-ENG',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-FIL',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-MATH',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-SCI',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],
            [
                'program' => 'BSED-SOC',
                'department' => 'CEAS',
                'category' => 'Classroom Based Action Research'
            ],

            // CHTM
            [
                'program' => 'BSHM',
                'department' => 'CHTM',
                'category' => 'Research'
            ],
            [
                'program' => 'BSTM',
                'department' => 'CHTM',
                'category' => 'Research'
            ],
        ]);
    }
}
