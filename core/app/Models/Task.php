<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    /**
     * Обновление updated_at родительской цели
     * @var string[]
     */
    protected $touches = ['goal'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'goal_id',
        'title',
        'comment',
        'price',
        'schedule',
        'is_completed',
        'start_at',
        'deadline_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'start_at'     => 'datetime',
        'deadline_at'  => 'datetime',
    ];

    /**
     * Связь с таблицей goals.
     * Возвращает цель к которой привязана задача
     *
     * @return BelongsTo
     */
    public function goal():BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
