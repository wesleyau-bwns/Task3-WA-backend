<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\OrderController;

// Merchant-specific operations
Route::get('merchants/{merchant}/products', [MerchantController::class, 'products']);
Route::post('merchants/{merchant}/products', [MerchantController::class, 'addProduct']);

// Orders relevant to merchant
Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'update']);
