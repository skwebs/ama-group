<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');

    // protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', 'user')->middleware('auth:sanctum');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
    });
});
Route::controller(UserController::class)->prefix('v2')->group(function () {
    Route::get('user/{user}', 'show')->middleware('auth:sanctum');
    Route::get('users', 'index')->middleware('auth:sanctum');
    Route::post('create', 'store');
});
