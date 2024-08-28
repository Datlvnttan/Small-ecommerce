<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\View\AuthController;
use Modules\Auth\Http\Controllers\View\SocialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix'=>'auth'], function () {
    Route::get("login",[
        AuthController::class,
        "login"
    ])->name("login");
    Route::get("register",[
        AuthController::class,
        "register"
    ])->name("register");
    Route::get('/{drive}/callback', [SocialController::class,"loginCallback"])->name('web.social.loginCallback');
    Route::get('/{id}/verify', [AuthController::class,"verifyAccountEmail"])->name("view.verify-account");
    Route::get('/logout', [AuthController::class,'logout'])->name("web.auth.logout");
});

