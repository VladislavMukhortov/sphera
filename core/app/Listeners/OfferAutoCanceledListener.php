<?php

namespace App\Listeners;

use App\Events\OfferAutoCanceledEvent;
use App\Models\Profile\UserSetting;
use App\Models\UserNotification;
use App\Notifications\MentoringAutoCancelNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OfferAutoCanceledListener implements ShouldQueue
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
     * Занесение событий в базу и рассылка пуш-уведомлений
     *
     * @param OfferAutoCanceledEvent $event
     *
     * @return void
     */
    public function handle(OfferAutoCanceledEvent $event): void
    {
        $offer = $event->offer;
        $offer->user->notify(new MentoringAutoCancelNotification($offer, true));
        $offer->sender->notify(new MentoringAutoCancelNotification($offer));

        (new UserNotification())->storeMentoringNotification(
            $offer->user->id,
            $offer->sender->id,
            $offer->goal,
            $offer->type == UserSetting::NOTIFICATIONS[UserSetting::OFFER_BECOME_MENTOR]
                ? UserSetting::NOTIFICATIONS[UserSetting::BECOME_MENTOR_DECLINED]
                : UserSetting::NOTIFICATIONS[UserSetting::BECOME_STUDENT_DECLINED]
        );

        $offer->update(['status' => 'declined']);
        $offer->delete();
    }
}
