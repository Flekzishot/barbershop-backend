<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WalletController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile', [ProfileController::class, 'update']);

        Route::get('/listings', [ListingController::class, 'index']);
        Route::post('/listings', [ListingController::class, 'store']);
        Route::get('/listings/{id}', [ListingController::class, 'show']);
        Route::put('/listings/{id}', [ListingController::class, 'update']);
        Route::delete('/listings/{id}', [ListingController::class, 'destroy']);
        Route::post('/listings/{id}/boost', [ListingController::class, 'boost']);

        Route::get('/wallet', [WalletController::class, 'show']);
        Route::get('/credit-packs', [WalletController::class, 'packs']);
        Route::post('/wallet/purchase', [WalletController::class, 'purchase']);
        Route::post('/wallet/spend', [WalletController::class, 'spend']);
    });
});
