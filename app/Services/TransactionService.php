<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Utils\PriceUtil;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TransactionService
{

    public function getTransactionsByFilters(array $filters): Collection|array
    {
        return Transaction::query()
            ->where('user_id', $filters['user']->id)
            ->limit($filters['limit'])
            ->orderByDesc('created_at')
            ->get()
            ->each(function (Transaction $transaction) {
                $transaction->amount = PriceUtil::format($transaction->amount);
            });
    }

    public function create(User $user, string $type, float $amount, DateTime $date): Model|Transaction|null
    {
        return Transaction::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'transaction_date' => $date
        ]);
    }
}