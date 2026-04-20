<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
        'services' => [
            'database' => \Illuminate\Support\Facades\DB::connection()->getPdo() ? 'connected' : 'disconnected',
            'redis' => \Illuminate\Support\Facades\Redis::connection()->ping() ? 'connected' : 'disconnected',
        ],
    ]);
});

// Subscription Mock Endpoints
Route::patch('/subscriptions/{subscription}/pause', function (\App\Models\Subscription $subscription) {
    $subscription->update(['status' => 'paused']);
    return response()->json(['message' => 'Subscription paused successfully', 'status' => 'paused']);
});
