<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Country::all() as $country) {
            City::factory(['country_id' => $country->id])
                ->hasLocale(1, ['lang' => 'ru'])
                ->hasLocale(1, ['lang' => 'en'])
                ->hasLocale(1, ['lang' => 'cn'])
                ->count(3)
                ->create();
        }
    }
}
