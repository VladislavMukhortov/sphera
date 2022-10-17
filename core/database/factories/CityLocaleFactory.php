<?php

namespace Database\Factories;

use Faker\Factory as FakerAlias;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityLocaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => function ($attributes) {
                return match ($attributes['lang']) {
                    'ru' => FakerAlias::create('ru_RU')->unique(true)->city,
                    'en' => FakerAlias::create('en_EN')->unique(true)->city,
                    'cn' => FakerAlias::create('zh_CN')->unique(true)->city,
                };
            },
        ];
    }
}
