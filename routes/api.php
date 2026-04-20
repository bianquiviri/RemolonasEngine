<?php

use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\StoreController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PickingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public routes
    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/plans/{plan}', [PlanController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::apiResource('subscriptions', SubscriptionController::class);
        
        // Orders workflow
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        
        // Roles based routes
        Route::middleware('role:supervisor|operator')->group(function () {
            Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        });

        Route::middleware('role:supervisor')->group(function () {
            Route::apiResource('stores', StoreController::class);
        });
        // Dashboards
        Route::get('/dashboard/customer', [DashboardController::class, 'customer'])->middleware('role:customer');
        Route::get('/dashboard/operator', [DashboardController::class, 'operator'])->middleware('role:operator');
        Route::get('/dashboard/supervisor', [DashboardController::class, 'supervisor'])->middleware('role:supervisor');

        // Picking Workflow
        Route::prefix('picking')->group(function () {
            Route::get('/order/{order}', [PickingController::class, 'getOrderItems']);
            Route::post('/scan', [PickingController::class, 'scanItem']);
            Route::post('/complete/{order}', [PickingController::class, 'completePicking']);
        })->middleware('role:operator|supervisor');
    });
});
