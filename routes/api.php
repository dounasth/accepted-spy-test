<?php

use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\Api\LoginController;
use App\Infrastructure\Http\Controllers\Api\SpyController;

//  Free access endpoints
Route::middleware('api')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});

//  Endpoints for authenticated users only
Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::post('/spies', [SpyController::class, 'store'])->name('spies.create');
});
