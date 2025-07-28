<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('v1/posts', PostController::class);

Route::post('v1/auth/register', [AuthController::class, 'register']);
Route::get('v1/auth/login', [AuthController::class, 'login']);
Route::post('v1/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');