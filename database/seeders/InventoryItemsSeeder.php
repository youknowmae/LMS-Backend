<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use Database\Factories\InventoryItemFactory;

class InventoryItemsSeeder extends Seeder
{
    public function run()
    {
        InventoryItemFactory::new()->create([
            'accession_number' => '1001',
            'location' => 'BEP',
            'title' => 'Noli Me Tangere',
            'author' => 'Jose Rizal',
        ]);

        InventoryItemFactory::new()->create([
            'accession_number' => '1002',
            'location' => 'IT',
            'title' => 'El Filibusterismo',
            'author' => 'Jose Rizal',
        ]);

        InventoryItemFactory::new()->create([
            'accession_number' => '1003',
            'location' => 'MED',
            'title' => 'Noli Me Tangere',
            'author' => 'Jose Rizal',
        ]);

        InventoryItemFactory::new()->create([
            'accession_number' => '1004',
            'location' => 'COMM',
            'title' => 'Noli Me Tangere',
            'author' => 'Jose Rizal',
        ]);
    }
}