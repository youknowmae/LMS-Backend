<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use CatalogingDepartmentsTableSeeder;
use CatalogingProgramsTableSeeder;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('patrons')->insert([
            [
                'patron' => 'admin',
                'fines_if_overdue' => 15,
                'days_allowed' => 3,
                'hours_allowed' => 12,
                'materials_allowed' => 5
            ],
            [
                'patron' => 'Student (Online)',
                'fines_if_overdue' => 100,
                'days_allowed' => 7,
                'hours_allowed' => 0,
                'materials_allowed' => 3
            ],
            [
                'patron' => 'Student (Face to Face)',
                'fines_if_overdue' => 50,
                'days_allowed' => 0,
                'hours_allowed' => 3,
                'materials_allowed' => 3
            ],
            [
                'patron' => 'Faculty',
                'fines_if_overdue' => 25,
                'days_allowed' => 7,
                'hours_allowed' => 0,
                'materials_allowed' => 5
            ],
        ]);

        $this->call([
            ProgramSeeder::class,
            UserSeeder::class,
            LocationSeeder::class,
            BookSeeder::class,
            PeriodicalSeeder::class,
            ArticleSeeder::class,
            ProjectSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
