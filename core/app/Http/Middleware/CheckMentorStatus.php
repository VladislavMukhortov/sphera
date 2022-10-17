<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMentorStatus
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
        if ($request->user()->is_mentor) {
            return $next($request);
        }

        return $request->expectsJson() ? response()->json([
            'status' => false,
            'error' => 'Unauthenticated.',
            'errors' => (object)[
                'Auth' => 'Unauthenticated.'
            ],
        ], 401) : redirect()->guest(route('login'));
    }
}
