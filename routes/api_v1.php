<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
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
    //Route::post('login', [AuthController::class, 'login'])->name("login");
});

