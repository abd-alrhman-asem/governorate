<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Domain\Auth\Actions\SendPasswordResetOtpAction;
use App\Domain\Auth\Services\PasswordResetService;
use App\Domain\User\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{


    public function __construct(
        protected UserRepository $userRepository,
        protected SendPasswordResetOtpAction $passwordResetAction,
        protected PasswordResetService $passwordResetService,
    ){}

    public function sendOtp(ForgotPasswordRequest $request): JsonResponse
    {
        $this->passwordResetAction->execute($request->input('email'));
        return success('An OTP has been sent to your email address.');
    }

    /**
     * @throws ValidationException
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $resetToken = $this->passwordResetService->verifyOtp($request->validated());

        return success('OTP verified successfully.', [
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->passwordResetService->resetPassword($request->validated());

        return success('Your password has been reset successfully.');
    }
}
