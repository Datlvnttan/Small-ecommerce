<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\DiscountCouponApiController;
use Modules\Order\Http\Controllers\Api\FeedbackOrderApiController;
use Modules\Order\Http\Controllers\Api\OrderApiController;
use Modules\Order\Http\Controllers\Api\OrderManager\FeedbackPersonalApiController;
use Modules\Order\Http\Controllers\Api\OrderManager\OrderAdminApiController;
use Modules\Order\Http\Controllers\Api\OrderManager\OrderPersonalApiController;

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

// Route::middleware('auth:api')->get('/order', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'order'], function () {
    Route::post('/', [
        OrderApiController::class,
        'buildDataOrder',
    ])->name('api.order.build-data-order');
    Route::get('/checkout/{key}', [
        OrderApiController::class,
        'checkout',
    ])->name('api.order.checkout');
    Route::post('/checkout/{key}', [
        OrderApiController::class,
        'createDataOrder',
    ])->name('api.order.createDataOrder');

    Route::post('/checkout/{checkoutKey}/retry-payment', [
        OrderApiController::class,
        'retryOrderPaymentPaypal',
    ])->name('api.order.retryOrderPaymentPaypal');
    // Route::get('/track-order-detail', [OrderApiController::class, 'findTrackOrder'])->name('api.order.findTrackOrder');



    Route::get('/{id}', [OrderApiController::class, 'show'])->name('api.order.show');
    Route::patch('/{orderId}', [OrderApiController::class, 'cancelOrder'])->name('api.order.cancelOrder');
    Route::post('/cancel/{orderId}', [OrderApiController::class, 'cancelOrderGuestEnterOTP'])->name('api.order.cancelOrderGuestEnterOTP');
    Route::post('/cancel/{orderId}/resend', [OrderApiController::class, 'resendEmailCancelOrderGuest'])->name('api.order.cancel.resendEmailCancelOrderGuest');
    
    
    Route::post('/{orderId}/feedback/{skuId}', [FeedbackOrderApiController::class, 'store'])->name('api.order.feedback.store');
    Route::post('/{orderId}/feedback/{skuId}/update', [FeedbackOrderApiController::class, 'update'])->name('api.order.feedback.update');
    Route::delete('/{orderId}/feedback/{skuId}', [FeedbackOrderApiController::class, 'destroy'])->name('api.order.feedback.destroy');
});
Route::middleware(['auth.jwt'])->group(function () {

    Route::prefix('personal')->group(function () {
        Route::prefix('order')->group(function () {
            Route::get('/', [OrderPersonalApiController::class, 'index'])->name('api.order.personal.order.index');
            // Route::get('/{id}', [OrderPersonalApiController::class, 'show'])->name('api.order.personal.order.show');
            // Route::post('/{orderId}/feedback/{skuId}', [FeedbackPersonalApiController::class, 'store'])->name('api.order.personal.order.feedback.store');
            // Route::post('/{orderId}/feedback/{skuId}/update', [FeedbackPersonalApiController::class, 'update'])->name('api.order.personal.order.feedback.update');
            // Route::delete('/{orderId}/feedback/{skuId}', [FeedbackPersonalApiController::class, 'destroy'])->name('api.order.personal.order.feedback.destroy');
        });
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('order')->group(function () {
            Route::get('/', [OrderAdminApiController::class, 'index'])->name('api.order.admin.order.index');
            Route::get('/{id}', [OrderAdminApiController::class, 'show'])->name('api.order.admin.order.show');
            Route::patch('/{id}', [OrderAdminApiController::class, 'updateNextStatus'])->name('api.order.admin.order.updateStatus');
            // Route::post('/{orderId}/feedback/{skuId}', [FeedbackPersonalApiController::class, 'store'])->name('api.order.personal.order.feedback.store');
            // Route::post('/{orderId}/feedback/{skuId}/update', [FeedbackPersonalApiController::class, 'update'])->name('api.order.personal.order.feedback.update');
            // Route::delete('/{orderId}/feedback/{skuId}', [FeedbackPersonalApiController::class, 'destroy'])->name('api.order.personal.order.feedback.destroy');
        });
    });

});


Route::group(['prefix' => 'discount-coupon'], function () {
    Route::get('/{couponCode}', [
        DiscountCouponApiController::class,
        'getByCouponCode',
    ])->name('api.discount-coupon.getByCouponCode');
});
