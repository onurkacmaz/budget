<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{

    public function getTransactionsByFilters(array $filters): Collection|array
    {
        return Transaction::query()
            ->where('user_id', $filters['user']->id)
            ->limit($filters['limit'])
            ->orderByDesc('created_at')
            ->get();
    }
}
