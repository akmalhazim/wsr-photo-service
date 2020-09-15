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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::post('/signup', '\App\Http\Controllers\SignUpController');
Route::post('/login', '\App\Http\Controllers\SignInController');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', App\Http\Controllers\LogoutController::class);

    Route::resources([
        'photos' => \App\Http\Controllers\PhotoController::class
    ]);

    Route::post('/users/{user}/share', [\App\Http\Controllers\SharedPhotoController::class, 'create']);
    Route::get('/users', \App\Http\Controllers\IndexUserController::class);
});
