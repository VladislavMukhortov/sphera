<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FollowSearchMentorNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->title = "$user->first_name $user->last_name ищет ментора!";
        $this->body = '';
    }
}
