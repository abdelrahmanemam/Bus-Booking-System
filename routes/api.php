<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');

    Route::group(['middleware' => ['auth:api', 'scope:user']], function () {
        Route::post('/available-seats', [TripController::class, 'seats'])->name('available-seats');
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', [AdminController::class, 'login'])->name('admin-login');
    Route::post('/register', [AdminController::class, 'register'])->name('admin-register');

    Route::group(['middleware' => ['auth:admin', 'scope:admin']], function () {

    });
});
