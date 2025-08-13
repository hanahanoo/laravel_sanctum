<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    // crud lainnya
    Route::resource('/posts', \App\Http\Controllers\Api\PostController::class)->except(['create', 'edit']);
    Route::resource('/categories', \App\Http\Controllers\Api\CategoryController::class)->except(['create', 'edit']);
    Route::resource('/products', \App\Http\Controllers\Api\ProductController::class)->except(['create', 'edit']);
    Route::resource('/orders', \App\Http\Controllers\Api\OrderController::class);
    Route::get('/orders/{code}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
});
