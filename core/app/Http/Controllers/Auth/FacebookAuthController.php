<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\{SignInEvent, SignUpEvent};
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{RedirectResponse, Response};
use Illuminate\Support\{Facades\Auth, Str};
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class FacebookAuthController extends Controller
{
    /**
     * Редирект для авторизации через гугл
     *
     * @return RedirectResponse
     */
    public function redirectToProvider(): RedirectResponse
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Обработка ответа от гугла
     *
     * @return Response|RedirectResponse
     */
    public function handleProviderCallback(): Response|RedirectResponse
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
            $existUser = User::firstWhere('email', $facebookUser->email);

            if ($existUser) {
                Auth::loginUsingId($existUser->id);
                event(new SignInEvent($existUser));
            } else {
                $user = new User();
                $user->uuid = Str::uuid();
                $user->first_name = explode(' ', $facebookUser->name)[0]
                    ?? 'Пользователь ' . User::max('id')
                    + 1 . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $user->last_name = explode(' ', $facebookUser->name)[1] ?? null;
                $user->email = $facebookUser->email;
                $user->facebook_id = $facebookUser->id;
                $user->photo = $facebookUser->avatar;
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
