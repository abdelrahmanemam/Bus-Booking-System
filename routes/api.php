<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'index'])->name('login');

Route::group(['middleware' => 'auth:api'], function(){

});
