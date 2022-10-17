<?php

namespace App\Models\Profile;

use App\Models\{Skill, User};
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, Relations\HasMany};

class UserSkill extends Model
{
    use HasFactory;

    protected $table = 'user_skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'mentor',
        'title',
        'amount',
        'user_id',
        'parent_id',
        'skill_id',
    ];

    /**
     * Возвращает пользователя
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Связь с таблицей основных навыков
     *
     * @return BelongsTo
     */
    public function baseSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id', 'id');
    }

    /**
     * Возвращает все вложенные "детали" для категории
     *
     * @return HasMany
     */
    public function nestedUserSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class, 'parent_id', 'id');
    }
}
