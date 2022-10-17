<?php

namespace Database\Factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'skill_id' => random_int(1, 5),
            'title' => $this->faker->text(15),
            'description' => $this->faker->realText(30),
            'date' => $this->faker->dateTimeThisMonth,
            'auto' => false,
        ];
    }
}
