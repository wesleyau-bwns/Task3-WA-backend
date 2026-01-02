<?php

use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/refresh', [AdminAuthController::class, 'refresh']);

    Route::middleware('auth:admin-api')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
    });
});
