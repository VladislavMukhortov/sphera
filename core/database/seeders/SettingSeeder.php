<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert([
                ['parameter' => Setting::DEFAULT_RATING, 'value' => 3.5],
                ['parameter' => Setting::FOLLOW, 'value' => 1],
                ['parameter' => Setting::UNFOLLOW, 'value' => 1],
                ['parameter' => Setting::FORCE_UNFOLLOW, 'value' => 1],
                ['parameter' => Setting::NEW_GOAL, 'value' => 1],
                ['parameter' => Setting::NEW_META_GOAL, 'value' => 1],
                ['parameter' => Setting::GOAL_PROGRESS, 'value' => 1],
                ['parameter' => Setting::TOP_UP_BALANCE, 'value' => 1],
                ['parameter' => Setting::NEW_ACHIEVEMENT, 'value' => 1],
                ['parameter' => Setting::NEW_DAILY_REPORT, 'value' => 1],
                ['parameter' => Setting::NEW_GOAL_REPORT, 'value' => 1],
                ['parameter' => Setting::NEW_COMMENT_FOR_MENTOR, 'value' => 1],
                ['parameter' => Setting::NEW_STUDENT, 'value' => 1],
                ['parameter' => Setting::NEW_REQUEST_FOR_MENTOR, 'value' => 1],
                ['parameter' => Setting::NEW_FAVORITE_GOAL, 'value' => 0.5],
                ['parameter' => Setting::NEW_DRAFT_GOAL, 'value' => 0.25],
                ['parameter' => Setting::NEW_COMMENT, 'value' => 1],
                ['parameter' => Setting::NEW_COMMENT_ANSWER, 'value' => 0.05],
                ['parameter' => Setting::FOLLOW_AMOUNT, 'value' => 100],
            ]
        );
    }
}
