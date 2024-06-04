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
            'location_short' => 'FIL',
            'location_full' => 'Filipino'
        ],
        [
            'location_short' => 'FOR',
            'location_full' => 'Foreign'
        ],
        [
            'location_short' => 'MED',
            'location_full' => 'Medical'
        ],
        [
            'location_short' => 'COM',
            'location_full' => 'Computer'
        ]
    ]);
    }
}
