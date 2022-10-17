<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'          => rand(1, 40000),
            'parent_id'        => rand(1, 40000),
            'body'             => $this->faker->realText(255),
            'commentable_type' => 'able',
            'commentable_id'   => rand(1, 40000),
            'created_at'       => \Illuminate\Support\Carbon::now(),
            'updated_at'       => \Illuminate\Support\Carbon::now(),
        ];
    }
}
