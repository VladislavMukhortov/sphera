<?php

namespace Database\Factories;

use App\Models\GoalOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'action_button' => 'One repeat done!',
            'target_count' => $this->faker->randomDigitNotNull(),
            'unit' => $this->faker->randomElement(GoalOption::REPEAT_TYPES),
        ];
    }
}
