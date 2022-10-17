<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            SkillSeeder::class,
            UserSeeder::class,
            StaffSeeder::class,
            UserSettingSeeder::class,
            FeedbackSeeder::class,
            AchievementSeeder::class,
            CommentSeeder::class,
            FollowSeeder::class,
        ]);
    }
}
