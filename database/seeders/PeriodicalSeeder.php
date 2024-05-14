<?php

namespace Database\Seeders;

use App\Models\Periodical;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodicalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Periodical::factory()->count(5000)->create([
            'authors' => '["Ubaldo, Jay-vee", "Rizal, Jose"]'
        ]);
    }
}
