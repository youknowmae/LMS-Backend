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
            'role' => 'superadmin',

            'department' => 'Library Department',
            'position' => 'Head',
            'username' => 'superadmin',
            'password' => bcrypt('Admin123')
        ]);

        User::factory()->count(1)->create([
            'role' => 'admin',

            'department' => 'Library Department',
            'position' => 'Chief',
            'first_name' => 'Tony',
            'last_name' => 'Stark',
            'gender' => 'Male',
            'username' => 'admin',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->count(1)->create([
            'role' => 'staff',

            'department' => 'Library Department',
            'position' => 'idk',
            'username' => 'staff',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->count(1)->create([
            'role' => 'user',
            'username' => 'user',
            'gender' => 'Male',
            'password' => Hash::make('123'),
            'program_id' =>fn() => fake()->numberBetween(1, 10),
        ]);

        User::factory()->count(1)->create([
            'role' => 'user',
            'username' => 'user1',
            'gender' => 'Female',
            'password' => Hash::make('123'),
            'program_id' =>fn() => fake()->numberBetween(1, 10),
        ]);
        User::factory()->count(1)->create([
            'role' => 'user',
            'username' => 'user2',
            'gender' => 'Male',
            'password' => Hash::make('123'),
            'program_id' =>fn() => fake()->numberBetween(1, 10),
        ]);



    }
}
