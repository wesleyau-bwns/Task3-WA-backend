<?php

use App\Http\Controllers\Auth\MerchantAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [MerchantAuthController::class, 'register']);
    Route::post('/login', [MerchantAuthController::class, 'login']);
    Route::post('/refresh', [MerchantAuthController::class, 'refresh']);

    Route::middleware('auth:merchant-api')->group(function () {
        Route::post('/logout', [MerchantAuthController::class, 'logout']);
        Route::get('/merchant', [MerchantAuthController::class, 'merchant']);
    });
});
