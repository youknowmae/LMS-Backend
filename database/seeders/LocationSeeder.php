<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
        [
            'location' => 'FIL',
            'full_location' => 'Filipino'
        ],
        [
            'location' => 'FOR',
            'full_location' => 'Foreign'
        ],
        [
            'location' => 'MED',
            'full_location' => 'Medical'
        ],
        [
            'location' => 'EWAN',
            'full_location' => 'Bahala na kayo'
        ]
    ]);
    }
}
