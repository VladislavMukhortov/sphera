<?php

namespace App\Notifications;

use App\Models\Offer;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MentoringAutoCancelNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    /**
     * Заголовок уведомления
     * @var string
     */
    private string $title;

    /**
     * Тело уведомления
     * @var string
     */
    private string $body;

    /**
     * Уведомление об авто-отмене предложения менторства
     *
     * @param Offer $offer
     * @param bool $owner
     */
    public function __construct(Offer $offer, bool $owner = false)
    {
        $goal = $offer->goal;
        $user = $offer->user;
        $sender = $offer->sender;

        $this->title = 'Авто-отмена предложения';
        $this->body = $owner
            ? "Вы не приняли предложение менторства в течение 24 часов от $sender->first_name $sender->last_name по цели $goal->title, и, оно было автоматически отменено системой."
            : "$user->first_name $user->last_name не подтвердил ваше предложение менторства в течении 24 часов по цели $goal->title, и, оно было автоматически отменено системой";
    }
}
