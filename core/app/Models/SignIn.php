<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignIn extends Model
{
    protected $table = 'signins';

    /**
     *
     * Аттрибуты для заполнения
     *
     * who|enum('staff', 'user') - автор
     * who_id|int - id автора
     * ip|sting|max:15 - ip пользователя
     * is_mobile|bool - Является ли мобильным устройством (по умолчанию true)
     * location|string|max:32
     * region|string|max:32 - Географическая локация
     * device|string|max:32 - Тип устройства
     * os|string|max:32 - Операционная система
     * os_ver|string|max:32 - Версия операционной системы
     * browser|string|max:32 - Браузер
     * browser_ver|string|max:32 - Версия браузера
     * created_at|dataTime - время логина
     *
     * @var array
     *
     */
    protected $fillable = [
        'who',
        'who_id',
        'ip',
        'is_mobile',
        'location',
        'region',
        'device',
        'user_agent',
        'os',
        'os_ver',
        'browser',
        'browser_ver',
        'created_at'
    ];

    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    /**
     *
     * Отключает установку created_at и updated_at
     * @var bool
     *
     */
    public $timestamps = false;

    /**
     * Проверяем находится ли устройство онлайн или возвращаем дату последнего входа
     *
     * @return string
     */
    public function getLastActiveAttribute(): string
    {
        $currentSessionToken = \auth()->user()->currentAccessToken()->withoutRelations();
        $signInToken = \auth()->user()->tokens()->firstWhere('abilities->user_agent', $this->user_agent);

        return $currentSessionToken->token == $signInToken?->token
            ? 'Online'
            : date('d-m-Y', strtotime($this->created_at));
    }
}
