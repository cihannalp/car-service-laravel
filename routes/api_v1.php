<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\CarModelsController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register'])->name("register");
    Route::post('login', [AuthController::class, 'login'])->name("login");
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('accounts', [AccountController::class, 'index'])->name("accounts");
    Route::post('accounts/deposit', [AccountController::class, 'deposit'])->name('accounts.deposit');
    Route::post('accounts/withdraw', [AccountController::class, 'withdraw'])->name('accounts.withdraw');

    Route::get('services', [ServiceController::class, 'index'])->name('services');

    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
	Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');    

    Route::get('carModels', [CarModelsController::class, 'index'])->name('carModels');
});
