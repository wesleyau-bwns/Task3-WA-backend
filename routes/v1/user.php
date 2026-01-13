<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/refresh', [UserAuthController::class, 'refresh']);

    Route::middleware('auth:user-api')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
        Route::get('/user', [UserAuthController::class, 'user']);
        Route::get('/permissions', [UserAuthController::class, 'permissions']);
    });
});

Route::prefix('api')->middleware('auth:user-api')->group(function () {
    Route::get('/user/profile', [UserController::class, 'show']);
    Route::post('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
});
