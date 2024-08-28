<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'shipping', 'namespace' => 'Api'], function () {
    Route::group(['prefix' => 'shipping-method'], function () {
        Route::get('/', 'ShippingMethodApiController@index')->name('api.shipping.shipping-method.index');
        Route::get('/{id}', 'ShippingMethodApiController@show')->where('id', '[0-9]+')->name('api.shipping.shipping-method.show');
        // Route::get('country/{countryId}', 'ShippingMethodApiController@getExpenseByCountryId')->where('countryId', '[0-9]+')->name('api.shipping.shipping-method.country.getExpenseByCountryId');
        // Route::get('/{id}', 'OrderController@show');
        // Route::post('/', 'OrderController@store');
        // Route::put('/{id}', 'OrderController@update');
        // Route::delete('/{id}', 'OrderController@destroy');
    });
    Route::group(['prefix' => 'country',], function () {
        Route::get('/', 'CountryApiController@index')->name('api.shipping.country.index');
        Route::get('shipping-method/{countryId?}', 'CountryApiController@getDeliveryCostsByCountry')->where('id', '[0-9]+')->name('api.shipping.country.shipping-method.getDeliveryCostsByCountry');
        // Route::get('/{id}', 'CountryApiController@show')->name('api.shipping.shipping-method.show');
    });
});
