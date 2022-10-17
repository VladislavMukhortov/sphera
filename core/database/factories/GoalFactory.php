<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title'       => $this->faker->realText(255),
            'type'        => $this->faker->randomElement(Goal::TYPES),
            'status'      => $this->faker->randomElement(Goal::STATUSES),
            'skill_id'    => $this->faker->randomElement(Skill::all()->pluck('id')),
            'start_at'    => $this->faker->date(),
            'deadline_at' => $this->faker->date(),
        ];
    }
}
