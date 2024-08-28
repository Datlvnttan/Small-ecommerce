<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\Api\CartApiController;
use Modules\Cart\Http\Controllers\Api\FavoriteApiController;

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

// Route::middleware('auth:api')->get('/cart', function (Request $request) {
//     return $request->user();
// });  

// Route::resource('cart', CartApiController::class)->only('index', 'store', 'update')->names([
//     'index' => 'api.cart.index',
//     'store' => 'api.cart.store',
//     'update' => 'api.cart.update',
// ]);


Route::group(['prefix'=>'cart'],function () {
    Route::get('/', [
        CartApiController::class,
        'index',
    ])->name('api.cart.index');
    Route::post('/', [
        CartApiController::class,
        'store',
    ])->name('api.cart.store');
    Route::put('/{skuId}', [
        CartApiController::class,
        'update',
    ])->where('skuId', '[0-9]+')->name('api.cart.update');
    Route::delete('/{skuId}', [
        CartApiController::class,
        'destroy',
    ])->where('skuId', '[0-9]+')->name('api.cart.destroy');
});


Route::resource('favorite', FavoriteApiController::class)->names([
    'index' => 'api.favorite.index',
    'store' => 'api.favorite.store',
    'update' => 'api.favorite.update',
    'destroy' => 'api.favorite.destroy',
]);
