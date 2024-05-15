<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CirculationLog>
 */
class CirculationLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patron_type' => $this->faker->randomElement(['student', 'admin', 'faculty', 'graduate', 'staff', 'visitor']),
            'fines_if_overdue' => $this->faker->randomFloat(2, 0, 100), // Random float between 0 and 100
            'days_allowed' => $this->faker->numberBetween(1, 30), // Random number between 1 and 30
            'materials_allowed' => $this->faker->numberBetween(1, 10), // Random number between 1 and 10
            'hours_allowed' => $this->faker->numberBetween(1, 24), // Random number between 1 and 24
            'books_allowed' => $this->faker->numberBetween(1, 5), // Random number between 1 and 5
        ];
    }
}
