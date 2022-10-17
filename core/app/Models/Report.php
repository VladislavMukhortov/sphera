<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'description',
        'file',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

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
     * Получить связанную с отчетом цель
     *
     * @return BelongsTo
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
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
     * Связь с таблицей users
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
