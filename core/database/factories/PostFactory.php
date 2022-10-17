<?php

namespace Database\Factories;

use App\Models\{Goal, Post};
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
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
            'title'   => $this->faker->word,
            'amount'  => 999,
            'type'    => random_int(0, 1) == 1 ? Post::TYPES[Post::GOAL] : Post::TYPES[Post::MENTORING],
            'goal_id' => fn($attributes) => Goal::whereUserId($attributes['user_id'])->inRandomOrder()->first(),
        ];
    }
}
