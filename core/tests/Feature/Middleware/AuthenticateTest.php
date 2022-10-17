<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\Authenticate;
use App\Models\{User, Staff};
use Faker\Factory;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Route, Session};
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use JsonException;
use ReflectionClass;
use ReflectionException;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Проверка редиректа для неавторизованного пользователя
     * @test
     */
    public function при_отсутствующих_авторизационных_данных_редирект_302_на_маршрут_логина(): void
    {
        Route::get('auth-middleware-test', function () {})->middleware('auth:staff');

        tap(
            $this->get('auth-middleware-test'),
            static function (TestResponse $response) {
                $response->assertRedirect(route('login'));
                self::assertEquals(302, $response->getStatusCode());
            }
        );
    }

    /**
     * Проверка аутентификации Staff-маршрута
     * @test
     */
    public function успешная_аутентификация_staff_guard(): void
    {
        $user = Staff::create([
            'email' => 'example@domain.com',
            'password' => Hash::make('test'),
            'is_enable' => true,
        ]);
        $this->be($user, 'staff');

        Route::get('auth-middleware-test', function () {})->middleware('auth:staff');

        tap(
            $this->get('auth-middleware-test'),
            static function (TestResponse $response) {
                $response->assertOk();
            }
        );
    }

    /**
     * Попытка аутентификации на маршрут Staff без соответствующих прав
     * @test
     */
    public function отклоненная_аутентификация_staff_guard_запись_с_пометкой_отключения_302_редирект(): void
    {
        $user = Staff::create([
            'email' => 'example@domain.com',
            'password' => Hash::make('test'),
            'is_enable' => false,
        ]);

        Session::push('test', 'test');
        $this->be($user, 'staff');
        Route::get('auth-middleware-test', function () {})->middleware('auth:staff');

        tap(
            $this->get('auth-middleware-test'),
            static function (TestResponse $response) {
                $response->assertSessionMissing('test');
                $response->assertRedirect(route('login'));
                self::assertEquals(302, $response->getStatusCode());
            }
        );
    }

    /**
     * Проверка аутентификации пользовательского маршрута
     * @test
     */
    public function успешная_аутентификация_sanctum_guard(): void
    {
        $user = User::create([
            'email' => 'example@domain.com',
            'uuid'  => Str::uuid(),
        ]);
        $this->be($user, 'sanctum');
        Route::get('auth-middleware-test', function () {})->middleware('auth:sanctum');

        tap(
            $this->get('auth-middleware-test'),
            static function (TestResponse $response) {
                $response->assertOk();
                self::assertNull($response->headers->get('Authorization'));
            }
        );
    }

    /**
     * Проверка доступа для забаненного пользователя
     * @test
     * @throws JsonException
     */
    public function отклоненная_аутентификация_sanctum_guard_ban_403(): void
    {
        // Создадим юзера для теста
        $user = User::create([
            'email' => Factory::create()->freeEmail(),
            'uuid' => Str::uuid(),
            'is_banned' => true
        ]);

        // Создадим токен
        $user->createToken(Str::uuid())->plainTextToken;
        $this->be($user, 'sanctum')->withCookie('jwt', 'token')->disableCookieEncryption();

        // Создаем маршрут навесив middleware
        Route::get('auth-middleware-test', function () {})->middleware('auth:sanctum');

        tap(
            $this->get('auth-middleware-test'),
            static function (TestResponse $response) {
                $response->assertStatus(403);
                $response->assertJsonStructure([
                    'status',
                    'message',
                ]);
                $dataArr = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
                self::assertIsBool($dataArr['status']);
                self::assertIsString($dataArr['message']);
                self::assertEquals(0, auth()->user()->tokens()->count());
                $response->assertCookieExpired('jwt');
            }
        );
    }

    /**
     * Установка заголовка авторизации (успешная)
     * @test
     * @throws ReflectionException
     */
    public function успешная_установка_заголовка_authorization_при_присуствии_куки_jwt(): void
    {
        $request = new Request();
        $request->cookies->set('jwt', 'token');

        $middleware = new ReflectionClass(Authenticate::class);
        $method = $middleware->getMethod('setAuthorizationHeader');
        $method->setAccessible(true);

        $class = new Authenticate(new AuthManager(new Application()));
        $result = $method->invoke($class, $request);
        $this->assertArrayHasKey('authorization', $result->headers->all());
    }

    /**
     * Отсутствие заголовка авторизации
     * @test
     * @throws ReflectionException
     */
    public function отсутствие_заголовка_authorization_при_отсутствии_куки_jwt(): void
    {
        $request = new Request();
        $middleware = new ReflectionClass(Authenticate::class);
        $method = $middleware->getMethod('setAuthorizationHeader');
        $method->setAccessible(true);

        $class = new Authenticate(new AuthManager(new Application()));
        $result = $method->invoke($class, $request);

        $this->assertArrayNotHasKey('authorization', $result->headers->all());
    }
}
