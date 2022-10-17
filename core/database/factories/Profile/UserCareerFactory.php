<?php

namespace Database\Factories\Profile;

use App\Models\Profile\UserCareer;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserCareerFactory extends Factory
{
    protected $model = UserCareer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'company_name'  => $this->faker->company(),
            'position_name' => $this->faker->name(),
            'date_start'    => \Illuminate\Support\Carbon::now(),
            'date_end'      => \Illuminate\Support\Carbon::now()
        ];
    }
}
