<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalOption extends Model
{
    use HasFactory;

    protected $table = 'goals_options';

    /**
     * Типы повторов. lap - круговой, count - прогресс
     *
     * @var string[]
     */
    public const REPEAT_TYPES = [
        'lap',
        'count'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'action_button', 'target_count', 'unit',
    ];
}
