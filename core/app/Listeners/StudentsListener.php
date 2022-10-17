<?php

namespace App\Listeners;

use App\Events\{GoalFinishedEvent, NewGoalEvent};
use App\Models\{Profile\UserSetting, UserNotification};
use App\Notifications\{MentorGoalFinishedNotification, MentorGoalStartedNotification};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;

class StudentsListener implements ShouldQueue
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
                $notificationDB = UserSetting::NEW_MENTOR_GOAL;
                $notificationFCM = new MentorGoalStartedNotification($goal);
                break;
            case ($event instanceof GoalFinishedEvent):
                $notificationDB = UserSetting::MENTOR_PERSONAL_GOAL_FINISHED;
                $notificationFCM = new MentorGoalFinishedNotification($goal);
                break;
            default:
                $notificationDB = '';
                $notificationFCM = Notification::class;
                break;
        }

        $goal->user->students->each(function ($student) use ($goal, $notificationDB, $notificationFCM) {
            (new UserNotification())->storeNotification(
                $student->id,
                $goal,
                UserSetting::NOTIFICATIONS[$notificationDB]
            );
            if ($student->notificationAllowed($notificationDB)) {
                $student->notify($notificationFCM);
            }
        });
    }
}
