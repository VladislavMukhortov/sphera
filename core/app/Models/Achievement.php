<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    use HasFactory;

    /**
     * auto - автопубликация достижения
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'skill_id',
        'goal_id',
        'description',
        'date',
        'auto',
        'type',
    ];

    protected $casts = [
        'date' => 'datetime',
        'auto' => 'boolean',
    ];

    /**
     * Связь с таблицей goals.
     * Возвращает цель достижения
     *
     * @return BelongsTo
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    /**
     * Связь с таблицей skills.
     * Возвращает навык, в котором получено достижение
     *
     * @return BelongsTo
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Выборка достижений в зависимости от настроек приватности
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
            fn($q) => $q->whereHas('goal',
                fn($q) => $q->whereHas(
                    'user',
                    fn($qr) => $qr->whereHas(
                        'settings',
                        fn($qry) => $qry->where(fn($qry) => $qry->whereSetting('achievements_visible')->whereValue('all'))
                            ->orWhere(fn($qry) => $qry->whereSetting('achievements_visible')->whereValue('private')->where('id', 0))
                    )
                )->orWhereHas(
                    'user',
                    fn($qr) => $qr->whereHas(
                        'settings',
                        fn($qry) => $qry->where((fn($qry) => $qry->whereSetting('achievements_visible')->whereValue('mentors')))
                    )
                )->where('mentor_id', $userId)
                    ->orWhereHas(
                        'user',
                        fn($qr) => $qr->whereHas(
                            'settings',
                            fn($qry) => $qry->where((fn($qry) => $qry->whereSetting('achievements_visible')->whereValue('followers')))
                        )->whereHas(
                            'follows',
                            fn($qr) => $qr->where('following_id', $userId)
                        )
                    )
            )
        );
    }
}
