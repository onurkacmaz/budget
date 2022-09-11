<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Models\UserLoginSmsCode;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    private SmsService $smsService;

    public function __construct()
    {
        $this->smsService = new SmsService(new Twilio());
    }

    /**
     * @param $email
     * @return Model|User|null
     */
    public function getUserByEmail($email): Model|User|null {
        return User::query()->where('email', $email)->first();
    }

    /**
     * @throws ApiException
     */
    public function login(string $email, string $password): User {
        $user = $this->getUserByEmail($email);

        if (is_null($user) || !Hash::check($password, $user->getPassword())) {
            throw new ApiException("INVALID_CREDENTIALS", 422);
        }

        $token = $this->createToken($user);

        $user->setToken($token);

        return $user;
    }

    /**
     * @throws ApiException
     */
    public function register(string $name, string $email, string $password): User|Model {
        $user = $this->getUserByEmail($email);
        if (!is_null($user)) {
            throw new ApiException("USER_ALREADY_EXISTS", 422);
        }

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->setToken($this->createToken($user));
        return $user;
    }

    /**
     * @param User|Model $user
     * @return string
     */
    private function createToken(User|Model $user): string {
        return $user->createToken('api-token')->plainTextToken;
    }

    /**
     * @throws ApiException
     */
    public function sendResetPasswordEmail(string $email): bool
    {
        $user = $this->getUserByEmail($email);
        if (is_null($user)) {
            throw new ApiException("USER_NOT_FOUND", 404);
        }
        return true;
    }

    /**
     * @throws ApiException
     */
    public function retrieveToken(int $userId, string $plainTextToken): Model|User
    {
        $token = PersonalAccessToken::query()->where('token', hash('sha256', last(explode('|', $plainTextToken))))->first();
        if (is_null($token)) {
            throw new ApiException("INVALID_TOKEN", 404);
        }
        $user = User::query()->find($userId);
        $user->token = $plainTextToken;
        return $user;
    }

    /**
     * @throws ApiException
     */
    public function sendSMSVerificationCode(string $email, string $password): bool
    {
        $user = $this->getUserByEmail($email);

        if (is_null($user) || !Hash::check($password, $user->getPassword())) {
            throw new ApiException("INVALID_CREDENTIALS", 422);
        }

        $smsCode = $this->createSmsCode($user);

        $this->smsService->send($user->getPhone(), sprintf("Your verification code is: %s", $smsCode));

        return true;
    }

    /**
     * @param User $user
     * @return string
     */
    private function createSmsCode(User $user): string {
        $code = $this->smsService->generateCode();
        UserLoginSmsCode::query()->create([
            'user_id' => $user->getId(),
            'sms_code' => $code,
            'expired_at' => new DateTime('+5 minutes'),
        ]);

        return $code;
    }

    /**
     * @param User $user
     * @param string $smsCode
     * @return UserLoginSmsCode|Model|null
     */
    public function getLastLoginSMSCodeByUser(User $user, string $smsCode): UserLoginSmsCode|Model|null {
        return UserLoginSmsCode::query()
            ->where('user_id', $user->getId())
            ->where('sms_code', $smsCode)
            ->whereNull("used_at")
            ->first();
    }

    /**
     * @throws ApiException
     */
    public function verifySmsCode(string $email, string $smsCode): bool
    {
        $user = $this->getUserByEmail($email);

        if (is_null($user)) {
            throw new ApiException("USER_NOT_FOUND", 404);
        }

        $smsCode = $this->getLastLoginSMSCodeByUser($user, $smsCode);

        if (is_null($smsCode)) {
            throw new ApiException("INVALID_SMS_CODE", 422);
        }

        $smsCode->update([
            'used_at' => new DateTime(),
        ]);

        return true;
    }
}
