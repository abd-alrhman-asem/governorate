<?php

namespace App\Domain\Auth\Services;

use App\Domain\Auth\Actions\SendPasswordResetOtpAction;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class PasswordResetService
{
    public function __construct(
        protected SendPasswordResetOtpAction $sendOtpAction,
        protected UserRepository $userRepository,
    ){}


    /**
     * @throws ValidationException
     */
    public function verifyOtp(array $data): string
    {
        $otpCacheKey = 'password_reset_otp_' . $data['email'];
        $cachedOtp = Cache::get($otpCacheKey);
        if (!$cachedOtp || $cachedOtp != $data['otp']) {
            throw ValidationException::withMessages([
                'otp' => ['The provided OTP is invalid or has expired.'],
            ]);
        }

        $resetToken = Str::random(60);
        $tokenCacheKey = 'password_reset_token_' . $data['email'];
        Cache::put($tokenCacheKey, $resetToken, now()->addMinutes(10));
        Cache::forget($otpCacheKey);
        return $resetToken;
    }

    /**
     * @throws ValidationException
     */
    public function resetPassword(array $data)
    {
        $tokenCacheKey = 'password_reset_token_' . $data['email'];
        $cachedToken = Cache::get($tokenCacheKey);

        if (!$cachedToken || $cachedToken !== $data['reset_token']) {
            throw ValidationException::withMessages([
                'reset_token' => ['The reset token is invalid or has expired.'],
            ]);
        }

        $user = $this->userRepository->findByEmail($data['email']);
        $user->password = $data['password'];
        $user->save();

        Cache::forget($tokenCacheKey);
        return $user->createToken('auth_token')->plainTextToken;
    }
}
