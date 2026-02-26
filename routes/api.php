<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::resource('categories', CategoryController::class)->middleware('auth:sanctum');

Route::resource('products', ProductController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/products/{product}/add-stock', [ProductController::class, 'addStock']);
    Route::patch('/products/{product}/decrease-stock', [ProductController::class, 'decreaseStock']);
});

Route::resource('orders', OrderController::class)->middleware('auth:sanctum');