<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphToMany};

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'visible',
        'goal_id',
        'type'
    ];

    public const GOAL = 0;
    public const MENTORING = 1;
    public const REPORT = 2;

    /**
     * Типы публикаций
     *
     * @var array
     */
    public const TYPES = [
        self::GOAL => 'goal',
        self::MENTORING => 'mentoring',
        self::REPORT => 'report',
    ];

    /**
     * Возвращает автора поста
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Полиморфная связь с таблицей goals.
     * Возвращает цели
     *
     * @return BelongsTo
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }

    /**
     * Полиморфная связь с таблицей skills.
     * Возвращает все прикрепленные теги
     *
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Skill::class, 'taggable', 'tags');
    }
}
