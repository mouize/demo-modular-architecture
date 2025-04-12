<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\CommentController;
use Modules\Blog\Http\Controllers\PostController;
use Modules\Blog\Http\Controllers\UserController;

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('comments', CommentController::class);
