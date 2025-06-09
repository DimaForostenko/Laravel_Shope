<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Group for secure routes (only for authenticated users)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
 
 Route::apiResource('products', ProductController::class);
    
 
 Route::apiResource('categories', CategoryController::class);
 
 
 Route::apiResource('comments', CommentController::class);
 
 
 Route::apiResource('orders', OrderController::class);
 Route::get('orders/history', [OrderController::class, 'history']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
// Public routes for viewing goods
Route::post('/products/{product}/comments', [CommentController::class, 'store']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}/products', [CategoryController::class, 'products']);