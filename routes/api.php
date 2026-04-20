<?php

use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/plans/{plan}', [PlanController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('subscriptions', SubscriptionController::class);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
    });
});
