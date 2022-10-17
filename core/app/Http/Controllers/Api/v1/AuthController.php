<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\{Auth, Cookie, Notification};
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{CodeRequest, AuthRequest};
use App\Notifications\SendAuthCode;
use App\Channels\LogChannel;
use Psr\Container\{ContainerExceptionInterface, NotFoundExceptionInterface};
use App\Events\Auth\{SignInEvent, SignUpEvent};
use App\Models\{User, TempCode};

class AuthController extends Controller
{
    /**
     * Генерирует код для верификации устройства
     * и отправляет в зависимости от введённого типа
     *
     * @param CodeRequest $request
     *
     * @return Response
     */
    public function code(CodeRequest $request): Response
    {
        $type = $this->getTypeLogin();
        $code = TempCode::create([
            'login' => $request->login,
            'code' => TempCode::generateCode(),
        ]);

        if (config('app.debug')) {
            LogChannel::send($code, new SendAuthCode($type));
        } else {
            Notification::send($code, new SendAuthCode($type));
        }

        return response([
            'status' => true,
            'message' => 'Code has been sended via ' . $type
        ], 200);
    }

    /**
     * Регистрация
     *
     * @param AuthRequest $request
     *
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function auth(AuthRequest $request): Response
    {
        $user = User::firstOrCreate([
            $this->getTypeLogin() => $this->getLoginFormatter()
        ], [
            'first_name' => 'Пользователь ' . random_int(111111, 999999),
            'uuid' => Str::uuid(),
            'lang' => app()->getLocale() ?? config('app.locale')
        ]);

        if ($user->wasRecentlyCreated) {
            event(new SignUpEvent($user, $request->ip()));
        } else {
            event(new SignInEvent($user, $request->ip()));
        }

        $auth = $this->tokenize($user);

        return response([
            'status' => true,
            'data' => [
                'token' => $auth['token'],
                'default_name' => preg_match('/^Пользователь [1-9]+/', $user->first_name) || !$user->last_name,
            ]
        ], 200)->withCookie($auth['cookie']);
    }

    /**
     * Авторизует и создаёт токен для пользователя
     *
     * @param User $user
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function tokenize(User $user): array
    {
        Auth::guard('user')->login($user);
        $token = $user->createToken(
            request()->get('device_name', Str::uuid()),
            ['user_agent' => request()->header('user-agent')]
        )->plainTextToken;

        return [
            'cookie' => cookie('jwt', $token, 60 * 24 * 365, null, '.nca8sd67fhsa.ru', true, true, false, 'none'),
            'token' => $token
        ];
    }

    /**
     * Выход из аккаунта на текущем устройстве
     *
     * @return Response
     */
    public function logout(): Response
    {
        Auth::guard('sanctum')->user()->currentAccessToken()->delete();

        return response([
            'status' => true,
            'message' => 'You have successfully logged out of the current device'
        ], 200)->withCookie(Cookie::forget('jwt'));
    }

    /**
     * Форматируем логин в зависимости от его типа
     *
     * @return string
     */
    private function getLoginFormatter(): string
    {
        return $this->getTypeLogin() === 'phone'
            ? phone_format_convert(request()->login)
            : request()->login;
    }

    /**
     * Определяем тип логина
     *
     * @return string
     */
    private function getTypeLogin(): string
    {
        return email_or_phone(request()->login);
    }
}
