<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;

// Regular users
Route::get('users/{user}', [UserController::class, 'show']);
Route::get('users/{user}/orders', [UserController::class, 'orders']);

// Users can view their own orders
Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);
