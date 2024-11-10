<?php

use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\Api\LoginController;

//  Free access endpoints
Route::middleware('api')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});
