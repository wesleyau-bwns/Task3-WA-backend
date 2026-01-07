<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']); // registers a new admin
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);

    Route::middleware('auth:admin-api')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/admin', [AdminAuthController::class, 'admin']);
    });
});

Route::prefix('api')->middleware('auth:admin-api')->group(function () {
    Route::post('/user/register', [UserAuthController::class, 'register']); // registers a new user
    Route::apiResource('users', UserController::class);
});
