<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionsCollection;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * История транзакций пользователя и баланс
     *
     * @param Request $request
     *
     * @return TransactionsCollection
     */
    public function balance(Request $request): TransactionsCollection
    {
        return new TransactionsCollection($request->user()->transactions, $request->user()->currentBalance());
    }
}
