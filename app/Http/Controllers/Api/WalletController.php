<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use App\Utils\PriceUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(public WalletService $walletService){}

    public function view(Request $request): JsonResponse {
        $user = $request->user();

        $wallet = $this->walletService->getWalletByUser($user);

        if (is_null($wallet)) {
            $wallet = $this->walletService->createWallet($user);
        }

        $wallet->balance = PriceUtil::format($wallet->balance);

        return response()->json($wallet);
    }
}
