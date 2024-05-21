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
            'first_name' => 'Tony',
            'last_name' => 'Stark',
            'gender' => 'Male',
            'username' => 'admin',
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
            'program_id' => 1,
            'username' => 'user',
            'password' => Hash::make('123')
        ]);

        $userCount = 15;
        $currentUsersCount = User::count();
        $usersToCreate = max(0, $userCount - $currentUsersCount);
        if ($usersToCreate > 0) {
            for ($i = 1; $i <= $usersToCreate; $i++) {
                $username = 'user' . ($currentUsersCount + $i);
                User::factory()->create([
                    'role' => 'user',
                    'gender' => $i % 2 === 0 ? 'Male' : 'Female',
                    'program_id' => fn() => fake()->numberBetween(1, 10),
                ]);
            }
        }
        
        User::factory()->create([
            'role' => 'user',
            'id' => 202110194,
            'program_id' => 1,
            'first_name' => 'Charles John',
            'last_name' => 'Malit',
            'username' => '202110194',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202111304,
            'program_id' => 5,
            'first_name' => 'Don Ace',
            'last_name' => 'Rañada',
            'username' => '202111304',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202110876,
            'program_id' => 10,
            'first_name' => 'John Laurenze',
            'last_name' => 'Leguidleguid',
            'username' => '202110876',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202110878,
            'program_id' => 14,
            'first_name' => 'Eunice',
            'last_name' => 'Protasio',
            'username' => '202110878',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202110188,
            'program_id' => 22,
            'first_name' => 'Ehdrian',
            'last_name' => 'Lim',
            'username' => '202110188',
            'password' => Hash::make('123')
        ]);
    }
}