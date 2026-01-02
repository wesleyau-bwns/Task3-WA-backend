<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

// Admin-only management
Route::apiResource('users', UserController::class)->only(['index', 'show']);
Route::apiResource('merchants', MerchantController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
