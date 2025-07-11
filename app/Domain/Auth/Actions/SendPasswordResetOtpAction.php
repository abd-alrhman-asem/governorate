<?php

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Notifications\PasswordResetOtpNotification;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;

class SendPasswordResetOtpAction
{

    public function __construct(private UserRepository $userRepository)
    {

    }
    public function execute(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        $otp = random_int(100000, 999999);

        $cacheKey = "password_reset_otp_" . $email;

        Cache::put($cacheKey, $otp, now()->addMinutes(config('cacheSystem.expired.default')));
        $user->notify(new PasswordResetOtpNotification($otp));

    }
}
