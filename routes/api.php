<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\UserApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// Public access to products
Route::apiResource('products', ProductApiController::class);

// Public access to users
Route::get('/users', [UserApiController::class, 'index']);
Route::get('/users/{id}', [UserApiController::class, 'show']);

// Protected routes (if you want to add admin-only routes later)
Route::middleware('auth:sanctum')->group(function () {
    // Add admin-only product routes here if needed
});
