<?php

use App\Http\Controllers\API\Categories\CategoryController;
use App\Http\Controllers\API\Orders\OrderMessageController;
use App\Http\Controllers\API\Products\ProductController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware(['api.key', 'throttle:60,1'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/param/{param}', [ProductController::class, 'showByCode']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/order-email', [OrderMessageController::class, 'index']);
});
