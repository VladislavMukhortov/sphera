<?php

namespace App\Observers;

use App\Models\Goal;
use App\Models\Profile\UserSetting;
use Illuminate\Support\Facades\Log;

class UserSettingObserver
{
    /**
     * Handle the UserSetting "created" event.
     *
     * @param  \App\Models\Profile\UserSetting  $userSetting
     * @return void
     */
    public function created(UserSetting $userSetting)
    {

    }

    /**
     * Handle the UserSetting "updated" event.
     *
     * @param  \App\Models\Profile\UserSetting  $userSetting
     * @return void
     */
    public function updated(UserSetting $userSetting)
    {
        $status = $userSetting->setting;
        $status = str_replace('goals_', '', $status);
        $status = str_replace('_visible', '', $status);

        $goals = Goal::where('user_id', $userSetting->user_id)
            ->where('status', $status)
            ->get();
        Log::info($status);
        if ($goals->count() > 0) {
            foreach ($goals as $goal) {
                $goal->can_view = $userSetting->value;
                $goal->save();
            }
        }
    }

    /**
     * Handle the UserSetting "deleted" event.
     *
     * @param  \App\Models\Profile\UserSetting  $userSetting
     * @return void
     */
    public function deleted(UserSetting $userSetting)
    {
        //
    }

    /**
     * Handle the UserSetting "restored" event.
     *
     * @param  \App\Models\Profile\UserSetting  $userSetting
     * @return void
     */
    public function restored(UserSetting $userSetting)
    {
        //
    }

    /**
     * Handle the UserSetting "force deleted" event.
     *
     * @param  \App\Models\Profile\UserSetting  $userSetting
     * @return void
     */
    public function forceDeleted(UserSetting $userSetting)
    {
        //
    }
}
