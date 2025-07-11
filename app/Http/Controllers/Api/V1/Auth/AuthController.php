<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Domain\User\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,

    ){}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());
        $token = $user->createToken('auth-token')->plainTextToken;
        return successWithToken(
            $token,
            'User registered successfully.',
            ResponseAlias::HTTP_CREATED,
        );
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = $this->userRepository->findByEmail($request->input('email'));
        $token = $user->createToken('auth-token')->plainTextToken;

        return successWithToken(
            $token,
            'Login successful.'

        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return success('Logged out successfully.');
    }
}
