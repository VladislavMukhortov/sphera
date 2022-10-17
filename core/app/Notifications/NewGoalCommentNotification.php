<?php

namespace App\Notifications;

use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewGoalCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param string $goalTitle
     */
    public function __construct(string $goalTitle)
    {
        $this->title = 'Новый комментарий к цели';
        $this->body = $goalTitle;
    }
}
