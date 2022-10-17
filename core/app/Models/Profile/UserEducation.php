<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo};
use App\Models\User;

class UserEducation extends Model
{
    use HasFactory;

    protected $table = 'user_education';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'university',
        'speciality',
        'file',
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
