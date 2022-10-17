<?php

namespace App\Listeners;

use App\Notifications\CreateFeedbackNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FeedbackListener
{
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
     * Отправить уведомление о созданном отзыве ментору
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event): void
    {
        $mentor = $event->feedback->goalMentor()->first();

        $notification = new CreateFeedbackNotification($mentor, $event->feedback);
        info(__CLASS__, [1231]);
        $mentor->notify($notification);
    }
}
