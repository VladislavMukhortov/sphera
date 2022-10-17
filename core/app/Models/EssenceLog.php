<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EssenceLog extends Model
{
    protected $table = 'essence_logs';

    const WHO = [
        'App\Models\Staff'     => 'Админ',
        'App\Models\User'      => 'Пользователь',
    ];

    const TARGET = [
        'App\Models\Staff'     => 'Админ',
        'App\Models\User'      => 'Пользователь',
    ];

    const RESTRICTED_FIELDS_VALUES = [
        'pin', 'password'
    ];

    /**
     *
     * Аттрибуты для заполнения
     *
     * whoable_type|enum('staff','user') - автор действия
     * whoable_id - id автора
     * targetable_type|enum('staff', 'user') - предмет действия
     * targetable_id - id предмета действия
     * field_name - имя поля изменения
     * old_value - старое значение
     * new_value - новое значение
     *
     * @var array
     */
    protected $fillable = [
        'whoable_type', 'whoable_id', 'targetable_type', 'targetable_id', 'field_name', 'old_value', 'new_value'
    ];

    /**
     * Relations с одной из таблиц из self::WHO
     *
     * @return MorphTo
     */
    public function whoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relations с одной из таблиц из self::TARGET
     *
     * @return MorphTo
     */
    public function targetable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     *
     * Возвращает имя автора
     * Для вызова использовать $essence_log->whoer
     *
     * @return string
     */
    public function getWhoerAttribute(): string
    {
        return self::WHO[$this->whoable_type] ?? 'Неизвестно';
    }

    /**
     *
     * Возвращает имя цели
     * Для вызова использовать $essence_log->targeter
     *
     * @return string
     */
    public function getTargeterAttribute(): string
    {
        return self::TARGET[$this->targetable_type] ?? 'Неизвестно';
    }

    /**
     *
     * Возвращает имя поля
     * Для вызова использовать $essence_log->field
     *
     * @return string
     */
    public function getFieldAttribute(): string
    {
        return $this->targetable_type::FIELDNAMES[$this->field_name] ?? 'Неизвестно';
    }

    /**
     *
     * Возвращает старое значение
     * Для вызова использовать $essence_log->oldv
     *
     * @return string
     */
    public function getOldvAttribute(): string
    {
        return in_array($this->field_name, self::RESTRICTED_FIELDS_VALUES) ? '*****' : $this->old_value;
    }

    /**
     *
     * Возвращает новое значение
     * Для вызова использовать $essence_log->newv
     *
     * @return string
     */
    public function getNewvAttribute(): string
    {
        return in_array($this->field_name, self::RESTRICTED_FIELDS_VALUES) ? '*****' : $this->new_value;
    }
}
