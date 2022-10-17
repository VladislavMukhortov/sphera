<?php

namespace App\Models;

use App\Http\Resources\{GoalResource, ReportResource};
use App\Models\Profile\UserSetting;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, Relations\MorphTo};

class UserNotification extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'initiator_id',
        'type',
        'status',
        'amount',
    ];

    public const NEW = 0;
    public const VIEWED = 1;

    /**
     * Статусы уведомлений
     *
     * @var array
     */
    public const STATUSES = [
        self::NEW => 'new',
        self::VIEWED => 'viewed',
    ];

    /**
     * Полиморфная связь
     *
     * @return MorphTo
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Связь с таблицей users.
     * Возвращает пользователя
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с таблицей users.
     * Возвращает отправителя запроса/уведомления
     *
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    /**
     * Возвращает post id только для исходящего запроса менторства
     *
     * @return int|null
     */
    public function getPostIdAttribute(): ?int
    {
        return $this->notifiable->posts()->where('type', Post::TYPES[Post::MENTORING])->value('id') ?? null;
    }

    /**
     * Получить нужный ресурс, если уведомление связано с сущностью
     *
     * @return ReportResource|GoalResource|null
     */
    public function getTargetAttribute(): ReportResource|GoalResource|null
    {
        return match (true) {
            $this->notifiable instanceof Goal => GoalResource::make($this->notifiable),
            $this->notifiable instanceof Report => ReportResource::make($this->notifiable),
            default => null,
        };
    }

    /**
     * Получить url на связанную с уведомлением сущность
     *
     * @return string|null
     */
    public function getTargetLinkAttribute(): ?string
    {
        return match (true) {
            $this->notifiable instanceof Goal => route('goals.show', $this->notifiable),
            $this->notifiable instanceof Report => route('reports.show', $this->notifiable),
            default => null,
        };
    }

    /**
     * Записываем уведомление
     *
     * @param int $recipientId
     * @param Model $model
     * @param string $type
     *
     * @return void
     */
    public function storeNotification(int $recipientId, Model $model, string $type): void
    {
        in_array($type, UserSetting::COMMENTS)
            ? $model->notifications()
            ->create(['user_id' => $recipientId, 'type' => $type, 'initiator_id' => $model->user->id])
            : $model->notifications()
            ->firstOrCreate(
                ['user_id' => $recipientId, 'type' => $type],
                ['initiator_id' => $model->user->id]
            );
    }

    /**
     * Записываем в БД уведомление по менторству
     *
     * @param int $senderId
     * @param int $recipientId
     * @param Goal $goal
     * @param string $type
     * @param int|null $amount
     *
     * @return void
     */
    public function storeMentoringNotification(int $senderId, int $recipientId, Goal $goal, string $type, int $amount = null): void
    {
        $goal->notifications()->firstOrCreate(
            ['user_id' => $recipientId, 'type' => $type],
            ['initiator_id' => $senderId, 'amount' => $amount],
        );
    }

    /**
     * Создаем запись в уведомлениях о поиске ментора для себя
     *
     * @param Goal $goal
     * @param int|null $amount
     *
     * @return Model
     */
    public function storeSearchMentor(Goal $goal, int $amount = null): Model
    {
        return $goal->notifications()->firstOrCreate([
            'user_id' => $goal->user_id,
            'initiator_id' => $goal->user_id,
            'type' => UserSetting::NOTIFICATIONS[UserSetting::SEARCH_MENTOR]
        ], [
            'amount' => $amount
        ]);
    }
}
