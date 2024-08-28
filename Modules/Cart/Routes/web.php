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
use Modules\Cart\Http\Controllers\View\CartController;

Route::prefix('cart')->group(function() {
    Route::get('/', [
        CartController::class,
        'index'
    ])->name('web.cart.index');
});
