<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use Notifiable, HasFactory;

    /**
     *
     * Название таблицы
     * @var string
     */
    protected $table = 'staff';

    /**
     *
     * Список аттрибутов, которые не будут попадать в коллекцию
     * @var array
     */
    protected $protected  = [
        'password', 'pin'
    ];

    /**
     *
     * Аттрибуты для заполнения
     *
     * name|string|max:64 - Имя
     * email|string|max:64 - E-mail
     * password|string - Пароль
     * pin|int - пин код для подтверждений действий
     * access_level|int - Уровень доступа админа
     * is_enable|bool - Разрешает/блокирует доступ к аккаунту (по умолчанию true)
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'pin', 'access_level', 'is_enable'
    ];

    const FIELDNAMES = [
        'name' => 'Имя',
        'email' => 'E-mail',
        'password' => 'Пароль',
        'pin' => 'Пин',
        'access_level' => 'Уровень доступа',
        'is_enable' => 'Активность'
    ];

    const OPERATOR = 50;
    const SUPERVISOR = 60;
    const SUPERADMIN = 150;

    const ROLES = [
        self::OPERATOR => 'Оператор',
        self::SUPERVISOR => 'Супервизор',
        self::SUPERADMIN => 'Суперадмин'
    ];

    /**
     * Relations с таблицей essence_logs
     *
     * @return HasMany
     */
    public function myEssenceLogs(): HasMany
    {
        return $this->hasMany(EssenceLog::class, 'whoable_id', 'id')->where('whoable_type', 'App\Models\Staff');
    }

    /**
     * Relations с таблицей signins
     *
     * @return HasMany
     */
    public function signins(): HasMany
    {
        return $this->hasMany(SignIn::class, 'who_id', 'id')->where('who', 'staff');
    }

    /**
     *
     * Возвращает имя роли
     * Для вызова использовать $staff->role
     *
     * @return string
     */
    public function getRoleAttribute(): string
    {
        return self::ROLES[$this->access_level] ?? 'Неизвестно';
    }

    /**
     *
     * Возвращает состояние активности
     * Для вызова использовать $staff->activity
     *
     * @return string
     */
    public function getActivityAttribute(): string
    {
        return  $this->is_enable ? 'Активен' : 'Заблокирован';
    }

    /**
     *
     * Возвращает css класс для состояние активности
     * Для вызова использовать $staff->activity_class
     *
     * @return string
     */
    public function getActivityClassAttribute(): string
    {
        return  $this->is_enable ? 'label-success' : 'label-error';
    }

    /**
     *
     * Проверяет, является ли пользователь оператором
     *
     * @return bool
     */
    public function isOperator(): bool
    {
        return $this->access_level == self::OPERATOR;
    }

    /**
     *
     * Ограничивает выборку только оператором.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOperators($query): Builder
    {
        return $query->where('access_level', self::OPERATOR);
    }

    /**
     *
     * Проверяет, является ли пользователь супервизором
     *
     * @return bool
     */
    public function isSupervisor(): bool
    {
        return $this->access_level == self::SUPERVISOR;
    }

    /**
     *
     * Ограничивает выборку только супервизором.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSupervisors($query): Builder
    {
        return $query->where('access_level', self::SUPERVISOR);
    }

    /**
     *
     * Проверяет, является ли пользователь суперадмином
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->access_level >= 150;
    }

    /**
     *
     * Ограничивает выборку только супер админом.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSuperAdmins($query): Builder
    {
        return $query->where('access_level', '>=', 150);
    }
}
