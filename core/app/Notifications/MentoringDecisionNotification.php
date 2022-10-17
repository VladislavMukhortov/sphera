<?php

namespace App\Notifications;

use App\Models\{Offer, User};
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MentoringDecisionNotification extends Notification implements ShouldQueue
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
     * @param string $decision
     */
    public function __construct(string $goalTitle, User $user, string $decision)
    {
        $this->title = $user->first_name . ' ' . $user->last_name;
        $this->body = $decision == Offer::STATUSES[Offer::ACCEPTED]
            ? 'Принял предложение менторства по цели:' . $goalTitle
            : 'Отклонил предложение менторства по цели:' . $goalTitle;
    }
}
