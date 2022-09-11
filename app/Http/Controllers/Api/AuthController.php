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
        $user = $authService->register($request->get('name'), $request->get('email'), $request->get('password'));
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
        $authService->sendSMSVerificationCode($request->get('email'), $request->get('password'));
        return response()->json(['message' => 'Sms sent']);
    }

    /**
     * @param VerifySmsCodeRequest $request
     * @param AuthService $authService
     * @return JsonResponse
     * @throws ApiException
     */
    public function verifySmsCode(VerifySmsCodeRequest $request, AuthService $authService): JsonResponse {
        $authService->verifySmsCode($request->get('email'), $request->get('smsCode'));
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
