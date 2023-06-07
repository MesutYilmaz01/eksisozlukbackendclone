<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group( function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });
});

Route::group(['prefix' => 'users'], function () {
    Route::post('register', [UserController::class, 'register']);
    Route::middleware('auth:api')->group( function () {
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('change-email', [UserController::class, 'changeEmail']);
        Route::post('change-personel-informations', [UserController::class, 'changePersonalInformations']);
        Route::post('change-biography', [UserController::class, 'changeBiography']);
        Route::post('delete', [UserController::class, 'deleteAccount']);
    });
});
