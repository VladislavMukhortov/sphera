<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo};
use App\Models\User;

class UserCareer extends Model
{
    use HasFactory;

    protected $table = 'user_career';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'position_name',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end'   => 'datetime',
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
}
