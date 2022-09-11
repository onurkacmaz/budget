<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransactionRequest;
use App\Services\TransactionService;
use App\Services\WalletService;
use App\Utils\PriceUtil;
use DateTime;
use Exception;
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

    /**
     * @throws ApiException
     */
    public function store(CreateTransactionRequest $request, TransactionService $transactionService): JsonResponse
    {
        try {
            $date = new DateTime($request->get('data'));
        } catch (Exception) {
            throw new ApiException('Invalid date format');
        }

        $user = $request->user();

        $transaction = $transactionService->create(
            $user,
            $request->get('type'),
            $request->get('amount'),
            $date,
        );

        return response()->json($transaction, 201);
    }
}
