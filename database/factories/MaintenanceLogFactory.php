<?php

namespace Database\Factories;

use App\Models\MaintenanceLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MaintenanceLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'activity' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
