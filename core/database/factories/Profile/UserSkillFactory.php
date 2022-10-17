<?php

namespace Database\Factories\Profile;

use App\Models\{Profile\UserSkill};
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSkillFactory extends Factory
{
    protected $model = UserSkill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
