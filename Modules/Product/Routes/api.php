<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\BrandApiController;
use Modules\Product\Http\Controllers\Api\CategoryApiController;
use Modules\Product\Http\Controllers\Api\ProductApiController;
use Modules\Product\Http\Controllers\Api\ProductAttributeOptionApiController;
use Modules\Product\Http\Controllers\Api\ProductFlashSaleApiController;
use Modules\Product\Http\Controllers\Api\SkuApiController;
use Modules\Product\Http\Controllers\Api\FeedbackApiController;

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

// Route::middleware('auth:api')->get('/product', function (Request $request) {
//     return $request->user();
// });


Route::get('/product-flash-sale', [
    ProductFlashSaleApiController::class,
    'index'
])->name('api.product-flash-sale.index');

Route::group(['prefix'=>'category'],function(){
    Route::get('/', [
        CategoryApiController::class,
        'index'
    ])->where('tag', 'hot')->name('api.category.index');
    Route::get('/tier/{categoryId?}',[
        CategoryApiController::class,
        'getSubcategories'
    ])->where('categoryId', '[0-9]+')->name('api.category.getSubcategories');
    Route::get('/path-to-root/{categoryId?}',[
        CategoryApiController::class,
        'getRecursiveParentSiblingsAndSelf'
    ])->where('categoryId', '[0-9]+')->name('api.category.getRecursiveParentSiblingsAndSelf');
    
    Route::get('/product-list',[
        ProductApiController::class,
        'filterProduct'
    ])->where('categoryId', '[0-9]+')->name('api.category.filterProduct');
});



Route::group(['prefix'=>'product'],function(){
    Route::get('/', [
        ProductApiController::class,
        'index'
    ])->where('tag', 'new|hot|sale')->name('api.product.index');
    Route::get('/{id}', [
        ProductApiController::class,
        'show'
    ])->where('id', '[0-9]+')->name('api.product.show');
    Route::get('/{productId}/feedback', [
        FeedbackApiController::class,
        'index'
    ])->where('id', '[0-9]+')->name('api.product.feedback');
    Route::get('/feedback-overview', [
        FeedbackApiController::class,
        'getFeedbackOverview'
    ])->name('api.product.feedback-overview');

    Route::get('hot-product-by-brand-id', [
        ProductApiController::class,
        'getHotProductByBrandId'
    ])->where('tag', 'new|hot|sale')->name('api.product.hot-product-by-brand-id');

    Route::get('filter', [
        ProductApiController::class,
        'filterProduct'
    ])->name('api.product.filterProduct');

    Route::group(['prefix'=>'product-attribute-option'],function(){
        Route::get('/', [
            ProductAttributeOptionApiController::class,
            'index'
        ])->name('api.product.product-attribute-option.index');
    });
    Route::prefix('sku')->group(function () {
        Route::get('/get-by-options',[
            SkuApiController::class,
            'getByOptions'
        ])->name('api.product.sku.get-by-options');
    });
    Route::get('/search', [
        ProductApiController::class, 'search'
    ])->name('api.product.search');
});

Route::group(['prefix'=>'brand'],function(){
    Route::get('/', [
        BrandApiController::class,
        'index'
    ])->where('tag', 'hot')->name('api.brand.index');
});


