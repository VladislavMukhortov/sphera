<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\{Builder, Collection, Factories\HasFactory};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use App\Models\Profile\{UserCareer, UserEducation, UserFamily, UserSetting, UserSkill};

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'email',
        'phone',
        'gender',
        'lang',
        'is_banned',
        'is_mentor',
        'first_name',
        'last_name',
        'full_name',
        'birthday',
        'country_id',
        'city_id',
        'google_id',
        'facebook_id',
        'photo',
        'rating',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birthday' => 'datetime',
    ];

    /**
     * Текущая должность
     *
     * @return string|null
     */
    public function getCurrentPositionAttribute(): ?string
    {
        $current_career = $this->career()->latest('date_start')->first();

        return $current_career->position_name ?? null;
    }

    /**
     * Выборка последней карьерной позиции
     *
     * @param Builder $query
     * @param string $searchText
     *
     * @return Builder
     */
    public function scopeCurrentPosition(Builder $query, string $searchText): Builder
    {
        return $query->whereHas(
            'career',
            fn($qr) => $qr
                ->join(
                    DB::raw(
                        "(SELECT MAX(date_start) as date FROM `user_career` GROUP BY user_id) as last_updates"
                    ),
                    'last_updates.date',
                    '=',
                    'user_career.date_start'
                )
                ->where('position_name', 'like', "%$searchText%")
        );
    }

    /**
     * Связь с таблицей user_career
     * Возвращает все места работы пользователя
     *
     * @return HasMany
     */
    public function career(): HasMany
    {
        return $this->hasMany(UserCareer::class);
    }

    /**
     * Связь с таблицей user_education
     * Возвращает все записи об учебе пользователя
     *
     * @return HasMany
     */
    public function education(): HasMany
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * Связь с таблицей user_skills
     *
     * @return HasMany
     */
    public function skills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Связь с таблицей user_skills
     * Навыки менторства.
     *
     * @return HasMany
     */
    public function mentorSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class)->whereNotNull('skill_id');
    }

    /**
     * Связь с таблицей user_skills
     * Увлечения
     *
     * @return HasMany
     */
    public function hobbySkills(): HasMany
    {
        return $this->hasMany(UserSkill::class)->whereNull(['skill_id', 'parent_id']);
    }

    /**
     * Связь с таблицей comments.
     * Возвращает все комментарии пользователя
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Связь с таблицей goals.
     * Возвращает все личные цели пользователя
     *
     * @return HasMany
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Связь с таблицей goals.
     * Возвращает все цели в которых пользователь является ментором
     *
     * @return HasMany
     */
    public function mentoredGoals(): HasMany
    {
        return $this->hasMany(Goal::class, 'mentor_id');
    }

    /**
     * Связь с таблицей user_family.
     * Возвращает список родственников пользователя
     *
     * @return HasMany
     */
    public function families(): HasMany
    {
        return $this->hasMany(UserFamily::class, 'user_id', 'id');
    }

    /**
     * Связь с таблицей user_settings.
     * Возвращает настройки пользователя
     *
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->HasMany(UserSetting::class);
    }

    /**
     * Проверяет, включено ли уведомление о конкретном действии
     *
     * @param int $notificationId
     *
     * @return bool
     */
    public function notificationAllowed(int $notificationId): bool
    {
        return $this->settings()->whereSetting('notifications')->where('value', 'like', "%/$notificationId/%")->exists();
    }

    /**
     * Связь с таблицей posts.
     * Возвращает все посты пользователя
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Связь с таблицей signins (логи входов)
     *
     * @return HasMany
     */
    public function signins(): HasMany
    {
        return $this->hasMany(SignIn::class, 'who_id', 'id')
            ->where('who', 'user')
            ->orderByDesc('created_at');
    }

    /**
     * Получить список устройств, на которых выполнен вход (выданы токены)
     *
     * @return Collection
     */
    public function getAuthorizedDevices(): Collection
    {
        return $this->tokens()
            ->whereNotNull('abilities->user_agent')
            ->get(['abilities->user_agent as user_agent', 'last_used_at AS last_used']);
    }

    /**
     * Получаем всех менторов пользователя
     *
     * @return BelongsToMany
     */
    public function mentors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'goals', 'user_id', 'mentor_id', 'id', 'id');
    }

    /**
     * Получаем всех учеников пользователя
     *
     * @return BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'goals', 'mentor_id', 'user_id', 'id', 'id');
    }

    /**
     * Связь с таблицей user_firebase_tokens
     * Получить токен
     *
     * @return HasOne
     */
    public function firebaseToken(): HasOne
    {
        return $this->hasOne(UserFirebaseToken::class);
    }

    /**
     * Получить все токены Firebase Messaging
     *
     * @return HasMany
     */
    public function firebaseTokens(): HasMany
    {
        return $this->hasMany(UserFirebaseToken::class);
    }

    /**
     * Токены для Firebase Messaging
     *
     * @return mixed
     */
    public function routeNotificationForFcm(): mixed
    {
        return $this->firebaseTokens->pluck('token')->all();
    }

    /**
     * Связь с таблицей notifications
     * Уведомления
     *
     * @return HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    /**
     * Relations с таблицей transactions
     * Транзакции пользователя
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Текущий баланс пользователя
     *
     * @return int
     */
    public function currentBalance(): int
    {
        return $this->transactions()
            ->select(DB::raw('SUM(CASE WHEN type < 200 THEN amount ELSE amount*-1 END) as amount'))
            ->where('user_id', $this->id)
            ->value('amount') ?? 0;
    }

    /**
     * Relations с таблицей users через follow
     * Возвращает подписчиков
     *
     * @return BelongsToMany
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Follow::class, 'user_id', 'following_id')->withTimestamps();
    }

    /**
     * Relations с таблицей users через follow
     * Возвращает тех, на кого подписан пользователь
     *
     * @return BelongsToMany
     */
    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, Follow::class, 'following_id')->withTimestamps();
    }

    /**
     * Relations с таблицей achievements
     * Возвращает достижения
     *
     * @return HasMany
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Relations с таблицей reports.
     * Возвращает все отчеты пользователя
     *
     * @return HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Relations с таблицей countries.
     * Возвращает параметры страны пользователя
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Ограничивает поиск по настройкам приватности
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeSearchVisible(Builder $builder): Builder
    {
        $userId = false;
        if (auth('sanctum')->user()) {
            $userId = auth('sanctum')->user()->id;
        }

        return $builder->where(
            fn($q) => $q->whereHas(
                'settings',
                fn($qr) => $qr->where(fn($qr) => $qr->whereSetting('search_visible')->whereValue('all'))
            )->orWhere(
                fn($q) => $q->whereHas(
                    'settings',
                    fn($qr) => $qr->where(fn($qr) => $qr->whereSetting('search_visible')->whereValue('mentors'))
                )->whereHas(
                    'goals',
                    fn($qr) => $qr->where('mentor_id', $userId)
                )
            )->orWhere(
                fn($q) => $q->whereHas(
                    'settings',
                    fn($qr) => $qr->where(fn($qr) => $qr->whereSetting('search_visible')->whereValue('followers'))
                )->whereHas(
                    'follows',
                    fn($qr) => $qr->where('following_id', $userId)
                )
            )->orWhere(
                fn($q) => $q->whereHas(
                    'settings',
                    fn($qr) => $qr->where(fn($qr) => $qr->whereSetting('search_visible')->whereValue('private')->where('id', 0))
                )
            )
        );
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->only('full_name');
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

    /**
     * Relations с таблицей cities.
     * Возвращает параметры города пользователя
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relations с таблицей favorites.
     * Возвращает избранные посты
     *
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Связь с таблицей activities.
     * Возвращает данные по активности пользователя в разрезе дней
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Связь с таблицей offers.
     * Возвращает запросы от пользователей, связанные с менторством
     *
     * @return HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
