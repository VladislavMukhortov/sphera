<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model, Relations\BelongsTo, Relations\HasOne};
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    /**
     * Атрибуты для заполнения
     *
     * user_id - id из таблицы users
     * type - тип транзакции, можно найти в config.balance
     * amount - сумма
     * who_id - id того, кто сделал транзакцию || null
     * target_id - id того из-за чего была сделана транзакция || null
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'who_id',
        'target_id',
    ];

    /**
     * Relation с таблицей users
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Возвращает отправителя транзакции
     *
     * @return HasOne
     */
    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'who_id');
    }

    /**
     * Возвращает адресата транзакции
     *
     * @return HasOne
     */
    public function recipient(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'target_id');
    }

    /**
     * Возвращает название транзакции по типу
     * Для вызова использовать $balance->named_type
     *
     * @return string
     */
    public function getNamedTypeAttribute(): string
    {
        return $this->type < 200
            ? (config('balance.debit_names')[$this->type] ?? 'Дебетовая транзакция')
            : (config('balance.credit_names')[$this->type] ?? 'Кредитовая транзакция');
    }

    /**
     * Возвращает сумму со знаком
     * Для вызова использовать $balance->signed_amount
     *
     * @return string
     */
    public function getSignedAmountAttribute(): string
    {
        return $this->type < 200
            ? $this->amount
            : '-' . $this->amount;
    }

    /**
     * Возвращает название операции в нужном языке
     * Для вызова использовать $balance->transaction_name
     *
     * @return string
     */
    public function getTransactionNameAttribute(): string
    {
        return $this->type < 200
            ? 'Дебетовая транзакция'
            : 'Кредитовая транзакция';
    }

    /**
     * Формирует текущий баланс для пользователя
     *
     * @param Builder $query
     * @param int|null $userId
     *
     * @return Builder
     */
    public function scopeBalance(Builder $query, ?int $userId = null): Builder
    {
        return $query->select(
            DB::raw('SUM(CASE WHEN type < 200 THEN amount ELSE amount*-1 END) as amount')
        )
            ->when(!$userId, fn($q) => $q->groupBy('user_id'))
            ->when($userId, fn($q) => $q->where('user_id', $userId));
    }
}
