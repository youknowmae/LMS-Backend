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
        ],
        [
            'location_short' => 'ABCOMM',
            'location_full' => null
        ],
        [
            'location_short' => 'CAD',
            'location_full' => null
        ],
        [
            'location_short' => 'EDUC',
            'location_full' => null
        ],
        [
            'location_short' => 'FS',
            'location_full' => null
        ],
        [
            'location_short' => 'HM',
            'location_full' => null
        ],
        [
            'location_short' => 'HM/TM',
            'location_full' => null
        ],
        [
            'location_short' => 'HRM/T',
            'location_full' => null
        ],
        [
            'location_short' => 'IGS',
            'location_full' => null
        ],
        [
            'location_short' => 'REF',
            'location_full' => null
        ],
        [
            'location_short' => 'TM',
            'location_full' => null
        ],
        [
            'location_short' => 'TM/HM',
            'location_full' => null
        ]
    ]);
    }
}
