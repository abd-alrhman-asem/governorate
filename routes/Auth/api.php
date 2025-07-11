<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your domain. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/auth')->group(function () {
    // Public routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
//    Route::post('forgot-password', [PasswordResetController::class, 'sendOtp'])->middleware('throttle:5,10');
    Route::post('forgot-password', [PasswordResetController::class, 'sendOtp']);
    Route::post('verify-otp', [PasswordResetController::class, 'verifyOtp']);
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
