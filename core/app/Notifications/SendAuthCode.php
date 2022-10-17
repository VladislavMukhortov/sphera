<?php

namespace App\Notifications;

use App\Models\TempCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendAuthCode extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private string $type)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return $this->type === 'phone' ? ['nexmo'] : ['email'];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues(): array
    {
        return [
            'mail'  => 'mail-queue',
            'nexmo' => 'nexmo-queue',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \App\Models\TempCode $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(TempCode $notifiable): MailMessage
    {
        $view = 'emails.code';
        $address = config('mail.from.address');
        $name = config('mail.from.name');

        return (new MailMessage)
            ->subject('Подтверждение входа')
            ->from($address, $name)
            ->view($view, ['code' => $notifiable->code]);
    }

//    public function toNexmo(TempCode $notifiable)
//    {
//        return (new NexmoMessage)->content('Code: ' . $notifiable->code);
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray(TempCode $notifiable)
    {
        return [
            'login' => $notifiable->login,
            'code'  => $notifiable->code
        ];
    }
}
