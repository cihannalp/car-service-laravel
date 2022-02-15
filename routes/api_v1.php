<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ServiceController;

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

Route::group(['prefix' => 'auth'] , function () {
    Route::post('register', [AuthController::class, 'register'])->name("register");
    Route::post('login', [AuthController::class, 'login'])->name("login");
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('accounts', [AccountController::class, 'index'])->name("accounts");
    Route::post('accounts/deposit',[AccountController::class, 'deposit'])->name('accounts.deposit');

    Route::get('services', [ServiceController::class, 'index'])->name('services');

    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
});


