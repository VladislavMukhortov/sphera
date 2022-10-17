<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FollowAddNewStudentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param User $mentor
     *
     * @return void
     */
    public function __construct(User $mentor)
    {
        $this->title = "$mentor->first_name $mentor->last_name стал ментором!";
        $this->body = '';
    }
}
