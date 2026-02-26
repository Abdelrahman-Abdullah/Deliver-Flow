<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// Public routes — no authentication needed
// -----------------------------------------------
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// -----------------------------------------------
// Protected routes — must be authenticated
// -----------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

});