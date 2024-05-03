<?php

namespace Database\Seeders;

use Database\Factories\ProjectAuthorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectAuthor;

class ProjectAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectAuthor::factory()->count(300)->create();
    }
}
