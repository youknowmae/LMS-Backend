<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash, Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(1)->create([
            'privilege' => 0,

            'department' => 'Library Department',
            'position' => 'Head'
        ]);

        User::factory()->count(1)->create([
            'privilege' => 1,

            'department' => 'Library Department',
            'position' => 'Chief'
        ]);

        User::factory()->count(3)->create([
            'privilege' => 2,

            'department' => 'CCS Department',
            'position' => 'Teacher'
        ]);

        User::factory()->count(3)->create([
            'course_id' => fake()->numberBetween(1, 4),
        ]);
    }
}
