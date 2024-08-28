<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Elastic\Http\Controllers\Api\ElasticsearchApiController;

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

Route::prefix('elastic')->group(function () {
    Route::get('/', [
        ElasticsearchApiController::class, 'index'
    ]);
    Route::get('/suggest', [
        ElasticsearchApiController::class, 'suggest'
    ])->name('api.elastic.suggest');
});
