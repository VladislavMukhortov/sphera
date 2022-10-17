<?php

namespace App\Models;

use App\Models\Profile\UserSkill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasOne, MorphToMany};

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    /**
     * Связь с таблицей skill_locales
     * Текущая локаль
     *
     * @return mixed
     */
    public function locale(): mixed
    {
        return $this->hasOne(SkillLocale::class, 'skill_id', 'id')->whereLang(app()->getLocale());
    }

    /**
     * Связь с таблицей skill_locales
     * Все существующие локали
     *
     * @return HasMany
     */
    public function locales(): HasMany
    {
        return $this->hasMany(SkillLocale::class);
    }

    /**
     * Возвращает пользователей
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_skills',
            'skill_id',
            'user_id'
        );
    }

    /**
     * Возвращает расширенную информацию о связи с пользователем
     *
     * @return HasOne
     */
    public function userSkill(): HasOne
    {
        return $this->hasOne(UserSkill::class, 'skill_id');
    }

    /**
     * Связь с таблицей goals
     *
     * @return HasMany
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Все посты, для которых категория указана как тег
     *
     * @return MorphToMany
     */
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable', 'tags');
    }
}
