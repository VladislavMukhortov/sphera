<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MentoringRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param string $goalTitle
     * @param User $user
     * @param string $role
     */
    public function __construct(string $goalTitle, User $user, string $role)
    {
        $this->title = $user->first_name . ' ' . $user->last_name;
        $this->body = $role == 'mentor'
            ? 'Запрашивает ваше менторство по цели:' . $goalTitle
            : 'Предложил менторство по цели:' . $goalTitle;
    }
}
