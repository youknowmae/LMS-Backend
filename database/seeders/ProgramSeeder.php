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
            [
                'program' => 'BSIT',
                'department' => 'CCS'
            ],
            [
                'program' => 'BSCS',
                'department' => 'CCS'
            ],
            [
                'program' => 'BSEMC',
                'department' => 'CCS'
            ],
            [
                'program' => 'ACT',
                'department' => 'CCS'
            ],
            [
                'program' => 'BSA',
                'department' => 'CBA'
            ],
            [
                'program' => 'BSBA',
                'department' => 'CBA'
            ],
            [
                'program' => 'BSN',
                'department' => 'CAHS'
            ],
            [
                'program' => 'BSM',
                'department' => 'CAHS'
            ],
            [
                'program' => 'BSED',
                'department' => 'CEAS'
            ],
            [
                'program' => 'BSHRM',
                'department' => 'CHTM'
            ],
            [
                'program' => 'BST',
                'department' => 'CHTM'
            ],
        ]);
    }
}
