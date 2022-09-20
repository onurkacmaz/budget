<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendResetPasswordEmailRequest;
use App\Http\Requests\SendSMSVerificationCodeRequest;
use App\Http\Requests\VerifySmsCodeRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * @throws ApiException
     */
    public function login(LoginRequest $request, AuthService $authService): LoginResource
    {
        $user = $authService->login($request->get('email'), $request->get('password'));
        return new LoginResource($user);
    }

    /**
     * @throws ApiException
     */
    public function register(RegisterRequest $request, AuthService $authService): RegisterResource {
        $user = $authService->register($request->get('name'), $request->get('email'), $request->get('password'), $request->get('phone'));
        return new RegisterResource($user);
    }

    /**
     * @throws ApiException
     */
    public function sendResetPasswordEmail(SendResetPasswordEmailRequest $request, AuthService $authService): JsonResponse {
        $authService->sendResetPasswordEmail($request->get('email'));
        return response()->json(['message' => 'Email sent']);
    }

    /**
     * @param SendSMSVerificationCodeRequest $request
     * @param AuthService $authService
     * @return JsonResponse
     * @throws ApiException
     */
    public function sendSMSVerificationCode(SendSMSVerificationCodeRequest $request, AuthService $authService): JsonResponse {

        if (!$request->has('phone') || empty($request->get('phone'))) {
            $user = $authService->getUserByEmail($request->get('email'));

            if (is_null($user) || !Hash::check($request->get('password'), $user->getPassword())) {
                throw new ApiException("INVALID_CREDENTIALS", 422);
            }

            if (!$user->isTwoFactorAuthEnabled()) {
                return response()->json(['isTwoFactorAuthEnabled' => $user->isTwoFactorAuthEnabled()]);
            }

            $phone = $user->getPhone();
        }else {
            $phone = $request->get('phone');
        }

        $authService->sendSMSVerificationCode($user ?? null, $phone);

        return response()->json(['message' => 'Sms sent']);
    }

    /**
     * @param VerifySmsCodeRequest $request
     * @param AuthService $authService
     * @return JsonResponse
     * @throws ApiException
     */
    public function verifySmsCode(VerifySmsCodeRequest $request, AuthService $authService): JsonResponse {
        $authService->verifySmsCode($request->get('smsCode'), $request->get('email'), $request->get('phone'));
        return response()->json(['message' => 'Sms verified']);
    }

    /**
     * @throws ApiException
     */
    public function retrieveToken(Request $request, AuthService $authService): JsonResponse {
        $user = $authService->retrieveToken($request->get('id'), $request->get('token'));
        return response()->json(['data' => $user]);
    }
}
