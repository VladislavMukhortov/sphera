<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = [
        'goal_id',
        'rank',
        'comment'
    ];

    use HasFactory;

    /**
     * @return HasOneThrough
     */
    public function goalMentor(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Goal::class,
            'id',
            'id',
            'goal_id',
            'mentor_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class, 'goal_id', 'id');
    }
}
