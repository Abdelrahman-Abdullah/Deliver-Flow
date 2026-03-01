<?php

use App\Http\Controllers\Api\{ AuthController, CategoryController, OrderController, ProductController, VendorController };
use App\Http\Controllers\Api\LocationController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// Public routes — no authentication needed
// -----------------------------------------------
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Public browsing
Route::get('vendors',                    [VendorController::class,  'index']);
Route::get('vendors/{vendor}',           [VendorController::class,  'show']);
Route::get('vendors/{vendor}/products',  [ProductController::class, 'index']);
Route::get('products/{product}',         [ProductController::class, 'show']);
Route::get('categories',                 [CategoryController::class,'index']);
Route::get('categories/{category}',      [CategoryController::class,'show']);

// -----------------------------------------------
// Protected routes — must be authenticated
// -----------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // Vendor management
    Route::post('vendors',            [VendorController::class, 'store']);
    Route::put('vendors/{vendor}',    [VendorController::class, 'update']);
    Route::delete('vendors/{vendor}', [VendorController::class, 'destroy']);

    // Product management — vendor only
    Route::post('products',             [ProductController::class, 'store']);
    Route::put('products/{product}',    [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // Category management — super_admin only
    Route::post('categories',               [CategoryController::class, 'store']);
    Route::put('categories/{category}',     [CategoryController::class, 'update']);
    Route::delete('categories/{category}',  [CategoryController::class, 'destroy']);

    // Order management — various permissions based on role 
    Route::get('orders',                              [OrderController::class, 'index']);
    Route::post('orders',                             [OrderController::class, 'store']);
    Route::get('orders/{order}',                      [OrderController::class, 'show']);
    Route::patch('orders/{order}/status',             [OrderController::class, 'updateStatus']);
    Route::patch('orders/{order}/assign-driver',      [OrderController::class, 'assignDriver']);
    Route::delete('orders/{order}',                   [OrderController::class, 'destroy']);

    // Location Tracking 
    Route::patch('driver/location',                    [LocationController::class, 'update']);
    Route::get('orders/{order}/location',              [LocationController::class, 'currentLocation']);
    Route::get('orders/{order}/location-history',      [LocationController::class, 'locationHistory']);
    Route::get('orders/{order}/distance',              [LocationController::class, 'distance']);
});