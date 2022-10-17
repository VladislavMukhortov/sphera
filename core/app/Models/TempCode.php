<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Auth;

class TempCode extends Model
{
    use Prunable;

    /**
     * Пределы лимитов создания кода (от \ до).
     */
    public const RANDOM_GENERATE_LIMITS = [
        'min' => 1000,
        'max' => 9999,
    ];

    protected $table = 'temp_codes';

    /**
     * Аттрибуты для заполнения
     *
     * login - email или телефон
     * code - проверочный код
     *
     * @var array
     */
    protected $fillable = [
        'login', 'code'
    ];

    /**
     * Список записей для удаления
     *
     * @return mixed
     */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMinutes(15));
    }

    /**
     * Проверяет есть ли данный код у авторизированного пользователя
     * или конкретного логина
     *
     * @param string|null $code
     * @param string|null $login
     * @return bool
     */
    protected static function checkCode(?string $code = null, ?string $login = null): bool
    {
        if (!$code) return false;

        $tempCode = self::latest()
            ->when($login, fn($q) => $q->where('login', $login))
            ->when(!$login && Auth::guard('sanctum')->user(), fn($q) => $q->whereIn('login', [
                Auth::guard('sanctum')->user()->email,
                Auth::guard('sanctum')->user()->phone
            ]))
            ->value('code');

        return $tempCode == $code;
    }

    /**
     * Генерация кода
     *
     * @return int
     */
    public static function generateCode(): int
    {
        return rand(
            self::RANDOM_GENERATE_LIMITS['min'],
            self::RANDOM_GENERATE_LIMITS['max']
        );
    }
}
