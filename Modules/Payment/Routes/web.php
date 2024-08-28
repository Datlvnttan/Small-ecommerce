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
use Modules\Payment\Http\Controllers\View\PaypalController;

Route::prefix('payment')->group(function() {
    Route::get('order-complete/{orderKey}', [
        PaypalController::class, 'orderComplete'
    ])->name('web.payment.paypal.complete');
    Route::get('cancel/{orderKey}', [
        PaypalController::class, 'cancel'
    ])->name('web.payment.paypal.order-cancel');
});
