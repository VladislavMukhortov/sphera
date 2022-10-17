<?php

namespace App\Listeners;

use App\Events\ActivityEvent;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ActivitiesListener implements ShouldQueue
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
     * Запись дневной активности пользователя
     *
     * @param ActivityEvent $event
     *
     * @return void
     */
    public function handle(ActivityEvent $event): void
    {
        $amount = Setting::firstWhere('parameter', $event->type)?->value ?? 0;
        $activity = $event->user->activities()->whereDate('created_at', $event->date)->firstOrCreate(['amount' => $amount]);
        $activity->wasRecentlyCreated ?: $activity->increment('amount', $amount);
    }
}
