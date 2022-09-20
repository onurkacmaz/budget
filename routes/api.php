<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/send-reset-password-email', [AuthController::class, 'sendResetPasswordEmail']);
Route::post('/auth/retrieve-token', [AuthController::class, 'retrieveToken']);
Route::post('/auth/send-sms-verification-code', [AuthController::class, 'sendSMSVerificationCode']);
Route::post('/auth/verify-sms-code', [AuthController::class, 'verifySmsCode']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'account'], function () {
        Route::get('/{id}', [AccountController::class, 'view']);
        Route::put('/{id}', [AccountController::class, 'update']);
        Route::delete('/{id}', [AccountController::class, 'destroy']);
    });
    Route::group(['prefix' => 'wallet'], function () {
        Route::get('/', [WalletController::class, 'view']);
    });
    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
        Route::delete('/', [TransactionController::class, 'destroyAll']);
    });
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/mark-read', [NotificationController::class, 'markRead']);
    });
    Route::put('/update-profile-picture/{id}', [AccountController::class, 'updateProfilePicture']);
});
