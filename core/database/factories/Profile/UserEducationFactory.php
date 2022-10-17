<?php

namespace Database\Factories\Profile;

use App\Models\Profile\UserEducation;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEducationFactory extends Factory
{
    protected $model = UserEducation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'university'  => $this->faker->company(),
            'speciality'  => $this->faker->jobTitle(),
            'file'        => $this->faker->uuid() . '.jpg',
            'date_start'  => $this->faker->date(),
            'date_end'    => $this->faker->randomElement([$this->faker->date, null])
        ];
    }
}
