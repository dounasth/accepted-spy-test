<?php

use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\Api\LoginController;
use App\Infrastructure\Http\Controllers\Api\SpyController;

//  Free access endpoints
Route::middleware('api')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/spies/random', [SpyController::class, 'random'])->name('spies.random')
        ->middleware('throttle:10,1');
});

//  Endpoints for authenticated users only
Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::post('/spies', [SpyController::class, 'store'])->name('spies.create');
});
