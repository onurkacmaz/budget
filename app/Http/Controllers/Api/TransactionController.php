<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Services\WalletService;
use App\Utils\PriceUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(public TransactionService $transactionService){}

    public function index(Request $request): JsonResponse {
        $user = $request->user();
        $limit = $request->get('limit');

        $transactions = $this->transactionService->getTransactionsByFilters([
            'user' => $user,
            'limit' => $limit
        ]);

        return response()->json($transactions);
    }
}
