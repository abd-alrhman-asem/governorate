<?php

namespace App\Domain\Auth\Actions;


use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginUserAction
{
    public function execute(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth-token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}
