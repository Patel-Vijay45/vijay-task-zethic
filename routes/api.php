<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\Authentication\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentWebhookController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;



// Route::group(['prefix' => 'v1', 'as' => 'v1'], function () {


// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/products', [ProductController::class, 'index'])->name('products-list');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories-list');
// Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('adminLogin'); // optional

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/webhook/fake-payment', PaymentWebhookController::class);
    Route::apiResource('/addresses', AddressController::class);
    Route::apiResource('orders', OrderController::class)->except(['delete', 'update']);
    // Route::get('/orders', [OrderController::class, 'index'])->name('order-index');
    // Route::post('/orders', [OrderController::class, 'store'])->name('order-store');
});


Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::apiResource('/categories', CategoryController::class)->except('index');
    Route::apiResource('/products', ProductController::class)->except('index');
    Route::apiResource('/users', UserController::class);

    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin-order');
});
