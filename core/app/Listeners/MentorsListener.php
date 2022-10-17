<?php

namespace App\Listeners;

use App\Events\{GoalFinishedEvent, NewGoalEvent};
use App\Models\{Profile\UserSetting, UserNotification};
use App\Notifications\{StudentGoalFinishedNotification, StudentGoalStartedNotification};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;

class MentorsListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Занесение событий в базу и рассылка пуш-уведомлений
     *
     * @param Event $event
     *
     * @return void
     */
    public function handle(Event $event): void
    {
        $goal = $event->goal;
        switch ($event) {
            case ($event instanceof NewGoalEvent):
                $notificationDB = UserSetting::NEW_STUDENT_GOAL;
                $notificationFCM = new StudentGoalStartedNotification($goal);
                break;
            case ($event instanceof GoalFinishedEvent):
                $notificationDB = UserSetting::STUDENT_PERSONAL_GOAL_FINISHED;
                $notificationFCM = new StudentGoalFinishedNotification($goal);
                break;
            default:
                $notificationDB = '';
                $notificationFCM = Notification::class;
                break;
        }

        $goal->user->mentors->each(function ($mentor) use ($goal, $notificationDB, $notificationFCM) {
            (new UserNotification())->storeNotification(
                $mentor->id,
                $goal,
                UserSetting::NOTIFICATIONS[$notificationDB]
            );
            if ($mentor->notificationAllowed($notificationDB)) {
                $mentor->notify($notificationFCM);
            }
        });
    }
}
