<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class LogChannel
{
    /**
     * Записывает результат метода $notification->toArray() в лог
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public static function send(mixed $notifiable, Notification $notification)
    {
        try {
            info($notification->toArray($notifiable));
        } catch (\Exception) {
            info(sprintf('Отправлено оповещение %s', get_class($notification)));
        }
    }
}
