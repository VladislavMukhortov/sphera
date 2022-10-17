<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $locale = $request->user()->lang;
        } elseif ($request->header('Locale')) {
            $locale = in_array($request->header('Locale'), config('app.locales'))
                ? $request->header('Locale')
                : 'ru';
        } else {
            $locale = $request->getPreferredLanguage(config('app.locales')) ?? 'ru';
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
