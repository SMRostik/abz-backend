<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::group(['namespace' => 'App\Http\Controllers\User'], function () {
        Route::get('/users', GetAllWithPaginationController::class);
        Route::get('/users/{userId}', GetByIdController::class);
        Route::post('/users', CreateController::class);
    });

    Route::group(['namespace' => 'App\Http\Controllers\Position'], function () {
        Route::get('/positions', GetAllController::class);
    });

    Route::group(['namespace' => 'App\Http\Controllers\Token'], function () {
        Route::get('/token', GenerateTokenController::class);
    });
});