<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'        => $this->faker->realText(255),
            'price'        => $this->faker->numberBetween(0, 100000),
            'schedule'     => '* * * * *',
            'is_completed' => $this->faker->boolean(),
            'start_at'     => \Illuminate\Support\Carbon::now(),
            'deadline_at'  => \Illuminate\Support\Carbon::now(),
        ];
    }
}
