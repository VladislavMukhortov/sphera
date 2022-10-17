<?php

namespace App\Notifications\Traits;

use NotificationChannels\Fcm\{FcmChannel, FcmMessage};
use NotificationChannels\Fcm\Resources\{AndroidConfig,
    AndroidFcmOptions,
    AndroidNotification,
    ApnsConfig,
    ApnsFcmOptions,
    Notification as CloudNotification
};

trait FcmNotification
{
    /**
     * @param $notifiable
     *
     * @return string[]
     */
    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return FcmMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        $notification = CloudNotification::create()
            ->setTitle($this->title)->setBody($this->body);

        return FcmMessage::create()
            ->setNotification($notification)
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios'))
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
