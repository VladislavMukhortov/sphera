<?php

namespace Database\Seeders;

use App\Models\{Country, CountryLocale};
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    private const COUNTRIES = [
        1 => [
            ['lang' => 'ru', 'name' => 'Россия'],
            ['lang' => 'en', 'name' => 'Russia'],
            ['lang' => 'cn', 'name' => '俄罗斯'],
        ],
        2 => [
            ['lang' => 'ru', 'name' => 'Казахстан'],
            ['lang' => 'en', 'name' => 'Kazakhstan'],
            ['lang' => 'cn', 'name' => '哈萨克斯坦'],
        ],
        3 => [
            ['lang' => 'ru', 'name' => 'Украина'],
            ['lang' => 'en', 'name' => 'Ukraine'],
            ['lang' => 'cn', 'name' => '乌克兰'],
        ],
        4 => [
            ['lang' => 'ru', 'name' => 'Беларусь'],
            ['lang' => 'en', 'name' => 'Belarus'],
            ['lang' => 'cn', 'name' => '白俄罗斯'],
        ],
        5 => [
            ['lang' => 'ru', 'name' => 'США'],
            ['lang' => 'en', 'name' => 'United States'],
            ['lang' => 'cn', 'name' => '美国'],
        ],
        6 => [
            ['lang' => 'ru', 'name' => 'Китай'],
            ['lang' => 'en', 'name' => 'China'],
            ['lang' => 'cn', 'name' => '中国'],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::factory()->createMany([
            ['default_lang' => 'ru'],
            ['default_lang' => 'ru'],
            ['default_lang' => 'ru'],
            ['default_lang' => 'ru'],
            ['default_lang' => 'en'],
            ['default_lang' => 'cn'],
        ]);

        foreach (Country::all() as $country) {
            foreach (self::COUNTRIES[$country->id] as $row) {
                CountryLocale::create($row + ['country_id' => $country->id]);
            }
        }
    }
}
