<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFirebaseToken extends Model
{
    /**
     * Название таблицы
     *
     * @var string
     */
    protected $table = 'user_firebase_tokens';

    /**
     * Аттрибуты для заполнения
     *
     * user_id - id пользователя
     * firebase_token - пуш токен firebase
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token'
    ];

    /**
     * Связь с таблицей users
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
