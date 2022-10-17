<?php

namespace Database\Factories\Profile;

use App\Models\{Profile\UserFamily, User};
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFamilyFactory extends Factory
{
    protected $model = UserFamily::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return random_int(0, 2) == 1
            ? [
                'relative_id'       => fn($attributes) => User::whereNotIn('id', [$attributes['user_id']])->first(),
                'is_child'          => $this->faker->boolean(),
                'since'             => \Illuminate\Support\Carbon::now(),
                'full_name'         => null,
                'photo'             => null,
                'position'          => null,
            ]
            : [
                'relative_id'       => null,
                'is_child'          => $this->faker->boolean(),
                'since'             => \Illuminate\Support\Carbon::now(),
                'full_name'         => $this->faker->name(),
                'photo'             => $this->faker->imageUrl(),
                'position'          => $this->faker->jobTitle(),
            ];
    }
}
