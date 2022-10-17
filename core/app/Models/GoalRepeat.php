<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalRepeat extends Model
{
    use HasFactory;

    protected $table = 'goals_repeats';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'count',
    ];

    /**
     * Обновление updated_at родительской цели
     * @var string[]
     */
    protected $touches = ['goal'];

    /**
     * Возвращает процент выполнения установленной дневной нормы
     *
     * @return int
     */
    public function getPercentAttribute(): int
    {
        $goal = Goal::find($this->goal_id);
        $dailyTarget = round($goal->option->target_count / $goal->start_at->diffInDays($goal->deadline_at, false), 2);

        return $this->count * 100 / $dailyTarget;
    }

    /**
     * Связь с таблицей goals.
     * Возвращает цель
     *
     * @return BelongsTo
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
