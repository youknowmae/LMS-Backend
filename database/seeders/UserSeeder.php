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
            'position' => 'Head',
            'username' => 'superadmin',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->create([
            'role' => 'cataloging',
            'position' => 'Chief',
            'username' => 'cataloging',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->create([
            'role' => 'frontdesk',
            'position' => 'Front Desk',
            'username' => 'front',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->create([
            'role' => 'opac',
            'position' => 'OPAC User',
            'username' => 'opac',
            'password' => Hash::make('Admin123')
        ]);

        User::factory()->count(20)->create();
        
        User::factory()->create([
            'role' => 'user',
            'id' => 202110194,
            'program_id' => 1,
            'gender' => 1,
            'first_name' => 'Charles John',
            'last_name' => 'Malit',
            'username' => '202110194',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202111304,
            'program_id' => 5,
            'gender' => 1,
            'first_name' => 'Don Ace',
            'last_name' => 'Rañada',
            'username' => '202111304',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202110876,
            'program_id' => 10,
            'gender' => 1,
            'first_name' => 'John Laurenze',
            'last_name' => 'Leguidleguid',
            'username' => '202110876',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202110878,
            'program_id' => 14,
            'gender' => 0,
            'first_name' => 'Eunice',
            'last_name' => 'Protasio',
            'username' => '202110878',
            'password' => Hash::make('123')
        ]);

        User::factory()->create([
            'role' => 'user',
            'id' => 202110188,
            'program_id' => 22,
            'gender' => 1,
            'first_name' => 'Ehdrian',
            'last_name' => 'Lim',
            'username' => '202110188',
            'password' => Hash::make('123')
        ]);
    }
}