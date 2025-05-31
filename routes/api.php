<?php

use App\Http\Controllers\Api\V1\Authentication\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;



// Route::group(['prefix' => 'v1', 'as' => 'v1'], function () {


// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('adminLogin'); // optional

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::group(['prefix' => 'admin', 'as' => 'admin'], function () {
    Route::apiResource('users', UserController::class);
});

Route::group(['prefix' => 'user', 'as' => 'user'], function () {
    Route::apiResource('users', UserController::class);
});
// });
