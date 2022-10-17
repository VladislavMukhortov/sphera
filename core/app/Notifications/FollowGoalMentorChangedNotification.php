<?php

namespace App\Notifications;

use App\Models\Goal;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FollowGoalMentorChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param Goal $goal
     *
     * @return void
     */
    public function __construct(Goal $goal)
    {
        $this->title = "Изменение цели {$goal->user->first_name} {$goal->user->last_name}";
        $this->body  = "Изменился ментор цели $goal->title";
    }
}
