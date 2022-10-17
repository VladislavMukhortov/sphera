<?php

namespace App\Models;

use App\Models\Profile\UserSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\HasOne;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'type',
        'user_id',
        'sender_id',
        'goal_id',
        'status',
    ];

    public const ACCEPTED = 1;
    public const DECLINED = 2;

    /**
     * Статусы запросов
     *
     * @var array
     */
    public const STATUSES = [
        self::ACCEPTED => 'accepted',
        self::DECLINED => 'declined',
    ];

    /**
     * Уведомления о запросах менторства
     *
     * @var array
     */
    public const TYPES = [
        UserSetting::NOTIFICATIONS[UserSetting::OFFER_BECOME_MENTOR],
        UserSetting::NOTIFICATIONS[UserSetting::OFFER_BECOME_STUDENT],
    ];

    /**
     * Получить отправителя запроса
     *
     * @return HasOne
     */
    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    /**
     * Получить адресата запроса
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Получить цель, на которой основан запрос
     *
     * @return HasOne
     */
    public function goal(): HasOne
    {
        return$this->hasOne(Goal::class, 'id', 'goal_id');
    }
}
