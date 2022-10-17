<?php

namespace Database\Factories;

use App\Models\{Country, Setting};
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'uuid'              => $this->faker->uuid(),
            'email'             => $this->faker->unique()->safeEmail(),
            'phone'             => $this->faker->phoneNumber(),
            'gender'            => $this->faker->randomElement(['male', 'female', 'other']),
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'birthday'          => \Illuminate\Support\Carbon::now(),
            'country_id'        => $this->faker->randomElement(Country::all()->pluck('id')),
            'rating'            => Setting::firstWhere('parameter', Setting::DEFAULT_RATING)->value('value'),
        ];
    }
}
