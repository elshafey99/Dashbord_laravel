<?php

use App\Http\Controllers\Apis\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('test', function () {
//     print 'test';
// });

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('create', [ProductController::class, 'create']);
    Route::get('edit/{id}', [ProductController::class, 'edit']);
    Route::post('store', [ProductController::class, 'store']);
    Route::put('update/{id}', [ProductController::class, 'update']); // put
    Route::post('delete/{id}', [ProductController::class, 'delete']); // delete
});