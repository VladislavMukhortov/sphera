<?php

namespace App\Listeners;

use App\Events\Auth\{SignInEvent, SignUpEvent};
use App\Models\SignIn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class LogInListener implements ShouldQueue
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
     * @param SignUpEvent|SignInEvent $event
     *
     * @return void
     */
    public function handle(SignUpEvent|SignInEvent $event): void
    {
        try {
            $location = Location::get($event->ip) ?: Location::get('85.91.195.145'); //todo <-- тестовый ip для локального использования
            $agent = new Agent();
            $accessLog['who'] = 'user';
            $accessLog['who_id'] = $event->user->id;
            $accessLog['ip'] = $event->ip;
            $accessLog['is_mobile'] = $agent->isMobile() == true ? 1 : 0;
            $accessLog['device'] = $agent->device();
            $accessLog['user_agent'] = $agent->getUserAgent();
            $accessLog['location'] = $location->countryName;
            $accessLog['region'] = $location->regionName;
            $accessLog['os'] = $agent->platform() ?? '';
            $accessLog['os_ver'] = $agent->version($accessLog['os']) ?? '';
            $accessLog['browser'] = $agent->browser() ?? '';
            $accessLog['browser_ver'] = $agent->version($accessLog['browser']) ?? '';
            $accessLog['created_at'] = now();
            SignIn::insert($accessLog);
        } catch (\Exception $e) {
            Log::error('AccessLog cant create insert', [$e->getMessage()]);
        }
    }
}
