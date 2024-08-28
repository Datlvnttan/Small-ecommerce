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
use Modules\Product\Http\Controllers\View\CategoryController;
use Modules\Product\Http\Controllers\View\ProductController;

Route::prefix('product')->group(function() {
    Route::get('/{id}', [
        ProductController::class,
        'show'
    ])->where('id', '[0-9]+')->name('web.product.show');
    Route::get('/search', [
        ProductController::class, 'search'
    ])->name('web.product.search');
});
Route::get('category/{categoryId?}',[
    CategoryController::class,
    'productList'
])->name('web.category.productList');

