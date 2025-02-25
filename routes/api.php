<?php

use App\Http\Controllers\Apis\Auth\EmailVerificationController;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Apis\Auth\RegisterController;
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

Route::group(['prefix' => 'users'], function () {
    Route::post('/register', [RegisterController::class, '__invoke']);
    Route::post('/send-code', [EmailVerificationController::class, 'sendCode']);
    Route::post('check-code', [EmailVerificationController::class, 'checkCode']);
    Route::delete('logout', [LoginController::class, 'logout']);
    Route::delete('logout-all-devices', [LoginController::class, 'logoutAllDevices']);
    Route::post('login', [LoginController::class, 'login']);
});
