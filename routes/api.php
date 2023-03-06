<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user'], function(){
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');

    Route::group(['middleware' => 'auth:api'], function(){

    });
});


Route::group(['middleware' => 'auth:api'], function(){

});
