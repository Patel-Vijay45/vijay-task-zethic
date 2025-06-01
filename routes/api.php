<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\Authentication\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentWebhookController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;



// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/products', [ProductController::class, 'index'])->name('products-list');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories-list');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/webhook/fake-payment', PaymentWebhookController::class);
    Route::apiResource('/addresses', AddressController::class)->except('show');
    Route::apiResource('orders', OrderController::class)->except(['delete', 'update']);
});


Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::apiResource('/categories', CategoryController::class)->except('show');
    Route::apiResource('/products', ProductController::class)->except('show');
    Route::apiResource('/users', UserController::class)->only('index');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin-order');
});
