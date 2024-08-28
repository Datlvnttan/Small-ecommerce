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
use Modules\Order\Http\Controllers\View\OrderController;
use Modules\Order\Http\Controllers\View\OrderManager\OrderPersonalController;

Route::prefix('order')->group(function () {
    Route::get('/track-order', [OrderController::class, 'trackOrder'])->name('web.order.trackOrder');
    Route::get('/find-track-order', [OrderController::class, 'findTrackOrder'])->name('web.order.findTrackOrder');
    Route::get('/track-order/{orderId}', [OrderController::class, 'orderDetails'])->name('web.order.track-order-details');
    
    Route::get('/checkout/{key}', [OrderController::class, 'checkout'])->name('web.order.checkout');
    Route::get('/checkout/{orderId}/success/{orderKey}', [OrderController::class, 'orderSuccess'])->name('web.order.checkout.order-success');
    Route::get('/{orderKey}/verify-order', [OrderController::class, 'verifyOrder'])->name('web.order.verify-order');
    Route::get('/{orderId}/cancel',[OrderController::class, 'cancelEnterOTP'])->name('web.order.cancelEnterOTP');
});


Route::middleware(['auth'])->group(function () {
    Route::prefix('personal')->group(function () {
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderPersonalController::class, 'index'])->name('web.order.personal.order.index');
            Route::get('/{id}', [OrderPersonalController::class, 'show'])->name('web.order.personal.order.show');
        });
       
    });
});
