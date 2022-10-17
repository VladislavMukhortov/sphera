<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoalRepeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'count' => 1,
            'created_at' => $this->faker->dateTimeThisMonth
        ];
    }
}
