<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param User $follower
     *
     * @return void
     */
    public function __construct(User $follower)
    {
        $this->title = 'У вас новый подписчик!';
        $this->body  = "На вас подписался $follower->first_name $follower->last_name";
    }
}
