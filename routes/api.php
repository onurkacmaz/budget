<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
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
    Route::put('/update-profile-picture/{id}', [AccountController::class, 'updateProfilePicture']);
});
