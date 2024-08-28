<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\AuthApiController;
use Modules\Auth\Http\Controllers\Api\GoogleApiController;
use Modules\Auth\Http\Controllers\Api\SocialApiController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthApiController::class,"login"])->name("api.login");
    Route::get('logout', [AuthApiController::class,"logout"]);
    Route::post('refresh', [AuthApiController::class,"refresh"])->name("refresh");
    Route::post('me', [AuthApiController::class,"me"])->name("me");
    Route::post('register', [AuthApiController::class,"register"])->name("api.register");

    Route::get('/{drive}', [SocialApiController::class,"getSocialSignInUrl"])->name("api.login.social");
    Route::group(["prefix"=>"social"], function (){
        
    });
});
