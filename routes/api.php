<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('forgot-password', 'forgotPassword');
        Route::post('reset-password', 'resetPassword');
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes that require authentication
        Route::controller(AuthController::class)->prefix('auth')->group(function () {
            Route::get('user', 'user');
            Route::post('logout', 'logout');
        });

        // User management routes
        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::get('/', 'index');
            Route::get('/{user}', 'show');
            Route::post('/', 'store');
        });
    });
});


