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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DemandsController;

Route::middleware('auth:api')->group( function () {
    Route::get('/loggedUser', function (Request $request) {
        return $request->user();
    });
    Route::resource('products', ProductsController::class);
    Route::resource('demands', DemandsController::class);
});

Route::middleware('guest')->group( function () {
    Route::post('/register', [ AuthController::class, 'register' ]);
    Route::post('/login', [ AuthController::class, 'login' ]);
});

Route::get('/get-products', [ ProductsController::class, 'index' ]);
