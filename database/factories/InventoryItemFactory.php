<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'barcode' => $this->faker->ean13,
            'accession_number' => $this->faker->unique()->numberBetween(1000, 9999),
            'author' => $this->faker->name,
            'location' => $this->faker->randomElement(['BEP', 'IT', 'MED', 'COMM']),
            'title' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}