<?php

namespace App\Listeners;

use App\Events\{FollowSearchMentorEvent, GoalFinishedEvent, GoalUpdatedEvent, MentorUpdatedEvent, NewGoalEvent};
use App\Models\{Profile\UserSetting, User, UserNotification};
use App\Notifications\{FollowAddNewStudentNotification,
    FollowGoalFinishedNotification,
    FollowGoalMentorChangedNotification,
    FollowGoalUpdatedNotification,
    FollowSearchMentorNotification,
    NewFollowGoalNotification};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;

class FollowersListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Флаг смены ментора
     * @var bool
     */
    private bool $mentorChanged = false;

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
                $notificationDB = UserSetting::NEW_FOLLOW_GOAL;
                $notificationFCM = new NewFollowGoalNotification($goal);
                break;
            case ($event instanceof GoalUpdatedEvent):
                $notificationDB = UserSetting::FOLLOW_GOAL_UPDATED;
                $notificationFCM = new FollowGoalUpdatedNotification($goal);
                break;
            case ($event instanceof GoalFinishedEvent):
                $notificationDB = UserSetting::FOLLOW_GOAL_FINISHED;
                $notificationFCM = new FollowGoalFinishedNotification($goal);
                break;
            case ($event instanceof MentorUpdatedEvent):
                $notificationDB = UserSetting::FOLLOW_GOAL_MENTOR_CHANGED;
                $notificationFCM = new FollowGoalMentorChangedNotification($goal);
                $this->mentorChanged = true;
                break;
            case ($event instanceof FollowSearchMentorEvent):
                $notificationDB = UserSetting::FOLLOW_SEARCH_MENTOR;
                $notificationFCM = new FollowSearchMentorNotification($goal->user);
                break;
            default:
                $notificationDB = '';
                $notificationFCM = Notification::class;
                break;
        }

        $goal->user->followers->each(function ($follower) use ($goal, $notificationDB, $notificationFCM) {
            (new UserNotification())->storeNotification(
                $follower->id,
                $goal,
                UserSetting::NOTIFICATIONS[$notificationDB]
            );
            $follower->notify($notificationFCM);
        });

        if ($this->mentorChanged && $mentor = User::find($goal->mentor_id)) {
            $mentor->followers->each(function ($follower) use ($goal, $mentor) {
                (new UserNotification())->storeNotification(
                    $follower->id,
                    $goal,
                    UserSetting::NOTIFICATIONS[UserSetting::FOLLOW_ADD_NEW_STUDENT]
                );
                $follower->notify(new FollowAddNewStudentNotification($mentor));
            });
        }
    }
}
