<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\{App, Auth, Cookie, Session};
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string[] ...$guards
     *
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards): mixed
    {
        if (Auth::guard('staff')->check() && Auth::guard('staff')->user()->is_enable === false) {
            Session::flush();
            Auth::guard('staff')->logout();
        }

        $request = $this->setAuthorizationHeader($request);
        $this->authenticate($request, $guards);

        if (Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->is_banned === true) {
            Auth::guard('sanctum')->user()->tokens()->delete();

            return response([
                'status' => false,
                'error' => "You're banned",
                'errors' => (object)[],
            ], 403)->withCookie(Cookie::forget('jwt'));
        }

        if (Auth::guard('sanctum')->check()) { //Устанавливаем локаль для api пользователя
            $locale = config('app.locales')
                ? Auth::guard('sanctum')->user()->lang
                : 'ru';
            App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Добавляем заголовок к запросу
     *
     * @param  Request  $request
     *
     * @return Request
     */
    private function setAuthorizationHeader(Request $request): Request
    {
        $jwt = $request->cookie('jwt');
        if (!empty($jwt)) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
        }

        return $request;
    }
}
