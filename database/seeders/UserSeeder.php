<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'role' => 'superadmin',
            'department' => 'Library Department',
            'position' => 'Head',
            'username' => 'superadmin',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->create([
            'role' => 'admin',
            'department' => 'Library Department',
            'position' => 'Chief',
            'username' => 'admin@gmail.com', // Hardcoded username
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->create([
            'role' => 'staff',
            'department' => 'Library Department',
            'position' => 'idk',
            'username' => 'staff', // Hardcoded username
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'username' => 'user', // Hardcoded username
            'password' => Hash::make('User123'),
            'department' => 'CCS Department',
            'position' => 'Teacher'
        ]);

        // Generate 3 additional users with random usernames
        User::factory(3)->create([
            'role' => 'user',
            'department' => 'CCS Department',
            'position' => 'Student'
        ]);
    }
}
