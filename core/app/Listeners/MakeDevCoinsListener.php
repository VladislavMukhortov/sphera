<?php

namespace App\Listeners;

use App\Events\Auth\SignUpEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MakeDevCoinsListener implements ShouldQueue
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
     * Добавляем внутреннюю валюту для локального тестирования
     *
     * @param SignUpEvent $event
     *
     * @return void
     */
    public function handle(SignUpEvent $event): void
    {
        if (in_array(config('app.env'), ['dev', 'stage', 'local'])) {
            $event->user->transactions()->create([
                'amount' => 1000,
                'type' => config('balance.debit.bonus')
            ]);
        }
    }
}
