<?php

namespace App\Listeners;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\{NewGoalCommentNotification,
    NewReportCommentNotification,
    UserGoalUpdatedNotification,
    UserGoalFinishedNotification
};
use App\Events\{GoalFinishedEvent, GoalUpdatedEvent, NewGoalCommentEvent, NewReportCommentEvent};
use App\Models\{Goal, Profile\UserSetting, UserNotification};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;

class UserSelfListener implements ShouldQueue
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
     * Занесение события в базу и отправка уведомления
     *
     * @param Event $event
     *
     * @return void
     */
    public function handle(Event $event): void
    {
        switch ($event) {
            case ($event instanceof GoalUpdatedEvent):
                $notificationDB = UserSetting::MY_GOAL_UPDATED;
                $notificationFCM = new UserGoalUpdatedNotification($event->goal->title);
                $targetModel = $event->goal;
                break;
            case ($event instanceof GoalFinishedEvent):
                $notificationDB = UserSetting::MY_GOAL_FINISHED;
                $notificationFCM = new UserGoalFinishedNotification($event->goal->title);
                $targetModel = $event->goal;
                $this->createAchievement($event->goal);
                break;
            case ($event instanceof NewGoalCommentEvent):
                $notificationDB = UserSetting::NEW_GOAL_COMMENT;
                $notificationFCM = new NewGoalCommentNotification($event->goal->title);
                $targetModel = $event->goal;
                break;
            case ($event instanceof NewReportCommentEvent):
                $notificationDB = UserSetting::NEW_REPORT_COMMENT;
                $notificationFCM = new NewReportCommentNotification($event->report);
                $targetModel = $event->report;
                break;
            default:
                $notificationDB = '';
                $notificationFCM = Notification::class;
                $targetModel = Model::class;
                break;
        }

        (new UserNotification())->storeNotification($targetModel->user->id, $targetModel, UserSetting::NOTIFICATIONS[$notificationDB]);

        if ($targetModel->user->notificationAllowed($notificationDB)) {
            $targetModel->user->notify($notificationFCM);
        }
    }

    /**
     * Создает достижение по факту выполнения цели
     *
     * @param Goal $goal
     *
     * @return void
     */
    protected function createAchievement(Goal $goal)
    {
        $goal->user->achievements()->create([
            'title' => 'Завершена цель',
            'description' => null,
            'skill_id' => $goal->skill_id,
            'goal_id' => $goal->id,
            'date' => $goal->updated_at,
        ]);
    }
}
