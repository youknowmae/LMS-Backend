<?php

use Illuminate\Database\Seeder;
use App\Models\Department;

class CatalogingDepartmentsTableSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['department' => 'CCS', 'full_department' => 'College of Computer Studies'],
            ['department' => 'CAHS', 'full_department' => 'College of Allied Health Studies'],
            ['department' => 'CEAS', 'full_department' => 'College of Education, Arts and Sciences'],
            ['department' => 'CHTM', 'full_department' => 'College of Hospitality and Tourism Management'],
            ['department' => 'CBA', 'full_department' => 'College of Business and Accountancy'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
