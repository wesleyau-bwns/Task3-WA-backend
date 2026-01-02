<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\MerchantAuthController;
use App\Http\Controllers\Auth\AdminAuthController;

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

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now(),
    ]);
});
