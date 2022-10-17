<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => 'example@domain.com',
            'name' => 'Test-admin',
            'password' => Hash::make(11111111),
            'access_level' => 150,
            'pin' => 123456,
            'is_enable' => 1,
        ];
    }
}
