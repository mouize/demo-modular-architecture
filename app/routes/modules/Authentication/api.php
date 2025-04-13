<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\AuthenticationController;

Route::post('register', [AuthenticationController::class, 'register'])
    ->name('register');
Route::post('login', [AuthenticationController::class, 'login'])
    ->name('login');
Route::middleware('signed')
    ->get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verify'])
    ->name('verification.verify');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout'])
        ->name('logout');
});
