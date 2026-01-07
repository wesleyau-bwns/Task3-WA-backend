<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);

    Route::middleware('auth:admin-api')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
    });
});

Route::prefix('api')->middleware('auth:admin-api')->group(function () {
    Route::apiResource('users', UserController::class);
});
