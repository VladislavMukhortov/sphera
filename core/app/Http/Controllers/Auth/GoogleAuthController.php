<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\{SignInEvent, SignUpEvent};
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{RedirectResponse, Response};
use Illuminate\Support\{Facades\Auth, Str};
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Редирект для авторизации через гугл
     *
     * @return RedirectResponse
     */
    public function redirectToProvider(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Обработка ответа от гугла
     *
     * @return Response|RedirectResponse
     */
    public function handleProviderCallback(): Response|RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $existUser = User::firstWhere('email', $googleUser->email);

            if ($existUser) {
                Auth::loginUsingId($existUser->id);
                event(new SignInEvent($existUser));
            } else {
                $user = new User();
                $user->uuid = Str::uuid();
                $user->first_name = $googleUser->user['given_name'];
                $user->last_name = $googleUser->user['family_name'];
                $user->lang = $googleUser->user['locale'];
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->photo = $googleUser->avatar;
                $user->save();
                Auth::loginUsingId($user->id);

                $user->createToken(
                    request()->get('device_name', Str::uuid()),
                    ['user_agent' => request()->header('user-agent')]
                );
                event(new SignUpEvent($user));
            }

            return redirect()->to('/');
        } catch (Throwable $e) {
            return new Response([
                'status' => false,
                'error' => 'Auth failed',
                'errors' => (object)[
                    'Auth' => $e->getMessage()
                ]
            ], 404);
        }
    }
}
