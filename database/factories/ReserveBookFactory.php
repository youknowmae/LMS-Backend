<?php

namespace Database\Factories;

use App\Models\ReserveBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReserveBook>
 */
class ReserveBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_id' => $this->faker->randomNumber(),
            'book_id' => $this->faker->randomNumber(),
        ];
    }
}
