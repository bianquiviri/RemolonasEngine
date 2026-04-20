<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/plans', [PlanController::class, 'index'])->name('plans');
