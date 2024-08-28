<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Api\BillingAddressApiController;
use Modules\User\Http\Controllers\Api\DeliveryAddressApiController;
use Modules\User\Http\Controllers\Api\UserApiController;

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

Route::middleware(['auth.jwt'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserApiController::class, 'profile'])->name('api.user.profile');
        Route::put('/profile', [UserApiController::class, 'update'])->name('api.user.profile.update');
        Route::patch('/profile/change-password', [UserApiController::class, 'changePassword'])->name('api.user.profile.changePassword');
        // Route::apiResource('', UserApiController::class)->names([
        //         "show"=>"api.user.show",
        //         "update"=>"api.user.update",
        //         "index"=>"api.user.index",
        //         "index"=>"api.user.index",
        //     ]);
        Route::group(['prefix' => 'delivery-address'], function () {
            Route::get('/', [DeliveryAddressApiController::class, 'index'])->name('api.user.delivery-address.index');
            Route::post('/', [DeliveryAddressApiController::class, 'store'])->name('api.user.delivery-address.store');
            Route::put('/{id}', [DeliveryAddressApiController::class, 'update'])->name('api.user.delivery-address.update');
            Route::patch('/{id}', [DeliveryAddressApiController::class, 'setAddressDefault'])->name('api.user.delivery-address.setAddressDefault');
            Route::delete('/{id}', [DeliveryAddressApiController::class, 'destroy'])->name('api.user.delivery-address.destroy');
        });
        Route::group(['prefix' => 'billing-address'], function () {
            Route::get('/', [BillingAddressApiController::class, 'index'])->name('api.user.billing-address.index');
            Route::post('/', [BillingAddressApiController::class, 'store'])->name('api.user.billing-address.store');
            Route::put('/{id}', [BillingAddressApiController::class, 'update'])->name('api.user.billing-address.update');
            Route::patch('/{id}', [BillingAddressApiController::class, 'setAddressDefault'])->name('api.user.billing-address.setAddressDefault');
            Route::delete('/{id}', [BillingAddressApiController::class, 'destroy'])->name('api.user.billing-address.destroy');
        });
    });
});
Route::prefix('user')->group(function () {
    Route::post('/profile/verify-change-email', [UserApiController::class, 'confirmVerifyChangeEmail'])->name('api.personal.profile.verifyChangeEmail');
});
