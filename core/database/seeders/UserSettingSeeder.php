<?php

namespace Database\Seeders;

use App\Models\{Profile\UserSetting, User};
use Illuminate\Database\Seeder;

class UserSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {
            $settings = [];
            foreach (UserSetting::PRIVACY_SETTINGS as $setting) {
                $rand = rand(1, 4);
                $value = null;
                switch ($rand) {
                    case 1:
                        $value = 'all';
                        break;
                    case 2:
                        $value = 'followers';
                        break;
                    case 3:
                        $value = 'mentors';
                        break;
                    case 4:
                        $value = 'private';
                        break;
                }
                $settings[] = [
                    'setting' => $setting,
                    'value' => $value,
                ];
            }
            $settings[] = [
                'setting' => 'notifications',
                'value' => '/' . implode('/', array_keys(UserSetting::NOTIFICATIONS)) . '/'
            ];
            $user->settings()->createMany($settings);
        });
    }
}
