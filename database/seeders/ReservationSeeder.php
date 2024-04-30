<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Truncate the table to ensure a clean state
        //Reservation::truncate();

        // Use the factory to create 10 reservations
        Reservation::factory()->count(10)->create();
    }
}
