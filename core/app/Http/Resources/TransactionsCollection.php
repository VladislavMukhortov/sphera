<?php

namespace App\Http\Resources;

class TransactionsCollection extends BaseCollection
{
    public $collects = TransactionResource::class;

    /**
     * Текущий баланс
     *
     * @var int
     */
    private int $currentBalance;

    /**
     * @param $resource
     * @param int $currentBalance
     */
    public function __construct($resource, int $currentBalance)
    {
        $this->currentBalance = $currentBalance;
        parent::__construct($resource);
    }

    /**
     * Добавляем информацию о балансе
     *
     * @param $request
     *
     * @return array
     */
    public function with($request): array
    {
        return [
            'balance' => $this->currentBalance,
        ];
    }
}
