<?php

namespace Database\Factories;

use App\Models\LockersLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class LockersLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LockersLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'lockerID' => $this->faker->unique()->randomNumber(),
            'status' => $this->faker->randomElement(['available', 'unavailable']),
            'date_time' => $this->faker->dateTime(),
        ];
    }
}
