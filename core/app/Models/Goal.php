<?php

namespace App\Models;

use App\Events\NewGoalEvent;
use Illuminate\Database\Eloquent\{Builder, Factories\HasFactory, Model};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne, MorphMany, MorphToMany};
use Laravel\Scout\Searchable;
use Spatie\Activitylog\{Traits\LogsActivity, LogOptions};

class Goal extends Model
{
    use HasFactory;
    use LogsActivity;
    use Searchable;

    public const SEARCHABLE_FIELDS = [
        'title',
    ];

//    protected $touches = [
//        'mentor',
//    ];

    /**
     * Запускаемые события
     *
     * @var string[]
     */
    protected $dispatchesEvents = [
        'created' => NewGoalEvent::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'user_id',
        'mentor_id',
        'skill_id',
        'type',
        'start_at',
        'deadline_at',
        'paused_at',
        'status',
        'full_name',
    ];

    /**
     * Связи, загружаемые по умолчанию
     *
     * @var string[]
     */
    protected $with = [
        'mentor',
        'skill',
        'tags'
    ];

    /**
     * Преобразование типов
     *
     * @var string[]
     */
    protected $casts = [
        'start_at' => 'datetime',
        'deadline_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    /**
     * Минимальный прогресс по цели, для установки статуса COMPLETE
     */
    public const COMPLETE_MARK = 90;

    /**
     * Типы целей
     */
    public const TYPE_REPEAT = 0;
    public const TYPE_LIST = 1;

    /**
     * Виды достижений. repeat - повторение, list - список
     *
     * @var string[]
     */
    public const TYPES = [
        self::TYPE_REPEAT => 'repeat',
        self::TYPE_LIST => 'list',
    ];

    public const STATUS_IN_PROGRESS = 0;
    public const STATUS_COMPLETE = 1;
    public const STATUS_PAUSED = 2;
    public const STATUS_OVERDUE = 3;
    public const STATUS_ENDING = 4;

    public const CAN_VIEW_ALL = 0;
    public const CAN_VIEW_MENTORS = 1;
    public const CAN_VIEW_FOLLOWERS = 2;
    public const CAN_VIEW_NONE = 3;
    /**
     * Глобальный статус достижения
     *
     * @var string[]
     */
    public const STATUSES = [
        self::STATUS_IN_PROGRESS => 'in_progress',
        self::STATUS_COMPLETE => 'complete',
        self::STATUS_PAUSED => 'paused',
        self::STATUS_OVERDUE => 'overdue',
        self::STATUS_ENDING => 'ending'
    ];

    public const CAN_VIEW = [
        self::CAN_VIEW_ALL => 'all',
        self::CAN_VIEW_MENTORS => 'mentors',
        self::CAN_VIEW_FOLLOWERS => 'followers',
        self::CAN_VIEW_NONE => 'none',
    ];

    /**
     * Возвращает процент выполнения цели
     *
     * @return int
     */
    public function getProgressAttribute(): int
    {
        if ($this->tasks()->count()) {
            return $this->tasks()->where('is_completed', true)->count() * 100 / $this->tasks()->count();
        } elseif ($this->repeats()->count()) {
            return $this->repeatProgress['percent'];
        } else {
            return 0;
        }
    }

    /**
     * Возвращает строковое значение статуса на основе количества выполненных задач
     *
     * @return string
     */
    public function getProgressStatusAttribute(): string
    {
        return $this->progress === 100 ?
            self::STATUSES[self::STATUS_COMPLETE] :
            self::STATUSES[self::STATUS_IN_PROGRESS];
    }

    /**
     * Возвращает информацию о прогрессе выполнения цели типа "Повтор"
     *
     * @return array|null
     */
    public function getRepeatProgressAttribute(): ?array
    {
        if ($this->repeats()->exists()) {
            $repeats_sum = $this->repeats()->sum('count');
            $target_count = $this->option->target_count;

            return [
                'target_count' => (int)$this->option->target_count,
                'current_count' => (int)$repeats_sum,
                'percent' => round($repeats_sum * 100 / $target_count),
            ];
        } else {
            return null;
        }
    }

    /**
     * Полиморфная связь с таблицей user_notifications
     *
     * @return MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(UserNotification::class, 'notifiable');
    }

    /**
     * Связь с таблицей users.
     * Возвращает пользователя прикрепленного к цели
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с таблицей users.
     * Возвращает пользователя-ментора прикрепленного к цели
     *
     * @return HasOne
     */
    public function mentor(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'mentor_id');
    }

    /**
     * Связь с таблицей tasks.
     * Возвращает все прикрепленные задачи
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Полиморфная связь с таблицей comments
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Связь с таблицей skills
     *
     * @return BelongsTo
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Полиморфная связь с таблицей skills.
     * Возвращает все прикрепленные тэги
     *
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Skill::class, 'taggable', 'tags');
    }

    /**
     * Связь с таблицей goals_repeats
     * Детали по цели с типом "Повтор"
     *
     * @return HasMany
     */
    public function repeats(): HasMany
    {
        return $this->hasMany(GoalRepeat::class, 'goal_id');
    }

    /**
     * Связь с таблицей goals_options
     * Подробные параметры цели
     *
     * @return HasOne
     */
    public function option(): HasOne
    {
        return $this->hasOne(GoalOption::class, 'goal_id');
    }

    /**
     * Связь с таблицей posts.
     * Возвращает публикации по цели
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Опции логирования действий с целью
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['mentor_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Выборка целей, по настройкам приватности пользователей
     *
     * @param Builder $builder
     * @param string $setting
     * @param string $status
     * @return Builder
     */
    public function scopeGoalsVisible(Builder $builder, string $setting, string $status): Builder
    {
        $userId = false;
        if (auth('sanctum')->user()) {
            $userId = auth('sanctum')->user()->id;
        }

        return $builder->where(
            fn($q) => $q->whereHas(
                'user',
                fn($q) => $q->whereHas(
                    'settings',
                    fn($qr) => $qr->where(fn($qr) => $qr->whereSetting($setting)->whereValue('all'))
                        ->orWhere(fn($qr) => $qr->whereSetting($setting)->whereValue('private')->where('id', 0))
                )
            )->orWhereHas(
                'user',
                fn($q) => $q->whereHas(
                    'settings',
                    fn($qr) => $qr->where(fn($qr) => $qr->whereSetting($setting)->whereValue('mentors'))
                )
            )->where(
                fn($q) => $q->where('status', $status)
                    ->where('mentor_id', $userId)
                    ->orWhere('status', '!=', $status)
            )->orWhereHas(
                'user',
                fn($q) => $q->whereHas(
                    'settings',
                    fn($qr) => $qr->where(fn($qr) => $qr->whereSetting($setting)->whereValue('followers'))
                )->whereHas(
                    'follows',
                    fn($q) => $q->where('following_id', $userId)
                )
            )->where(
                fn($q) => $q->where('status', $status)
            )->orWhere('status', '!=', $status)
        );
    }

//    /**
//     * Modify the query used to retrieve models when making all of the models searchable.
//     *
//     * @param  \Illuminate\Database\Eloquent\Builder  $query
//     * @return \Illuminate\Database\Eloquent\Builder
//     */
//    protected function makeAllSearchableUsing($query): Builder
//    {
//        return $query->with('mentor');
//    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->only('title');
    }

    /**
     * Индексация поля.
     *
     * @return mixed
     */
    public function getScoutKey(): mixed
    {
        return $this->id;
    }
}
