<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class WalletService
{
    public function getWalletByUser(User $user): Wallet|Model|null {
        return Wallet::query()->where('user_id', $user->id)->first();
    }

    public function createWallet(User|Model $user, float $balance = 0): Wallet|Model {
        return Wallet::query()->create([
            'user_id' => $user->id,
            'balance' => $balance
        ]);
    }

    public function updateWallet(Wallet $wallet, float $balance): Wallet|Model
    {
        $wallet->balance = $balance;
        $wallet->save();

        return $wallet;
    }
}
