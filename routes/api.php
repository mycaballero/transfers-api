<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Picking\PickingController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:sanctum'], function () {
     Route::post('logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'pickings'], function () {
        Route::get('', [PickingController::class, 'getPickings']);
    });
});
Route::group(['prefix' => 'users'], function () {
    Route::post('sing-up', [UserController::class, 'store']);
    Route::post('sign-in', [AuthController::class, 'signIn']);
});
