<?php

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

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\View\AddressController;
use Modules\User\Http\Controllers\View\UserController;



Route::group(["middleware" => "web"], function () {
    Route::get('user', [
        UserController::class, "index"
    ]);
    Route::prefix('user')->group(function () {
        Route::get('/profile/verify-change-email', [UserController::class, 'verifyChangeEmail'])->withoutMiddleware('auth')->name('web.user.verifyChangeEmail');
    });

});

Route::group(["middleware" => "auth"], function () {
    Route::prefix('personal')->group(function () {
        Route::get('/profile', [UserController::class, 'profile'])->name('web.personal.profile');
        Route::get('/address', [AddressController::class, 'index'])->name('web.personal.address');
    });
});
