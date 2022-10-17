<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'goal_id' => rand(1, 100000),
            'rank'    => rand(1, 5),
            'comment' => $this->faker->realText(255),
        ];
    }
}
