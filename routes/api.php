<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MerchantController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Users
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::get('users/{user}/orders', [UserController::class, 'orders']);

    // Merchants
    Route::apiResource('merchants', MerchantController::class)->only(['index']);
    Route::get('merchants/{merchant}/products', [MerchantController::class, 'products']);
    Route::post('merchants/{merchant}/products', [MerchantController::class, 'addProduct']);

    // Products
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);

    // Orders
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store', 'update']);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now(),
    ]);
});
