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

// Route::prefix('main')->group(function() {
//     Route::get('/', 'MainController@index');
// });

use Illuminate\Support\Facades\Route;
use Modules\Main\Http\Controllers\View\HomeController;

Route::get('/', [
    HomeController::class,
    'index'
])->name('web.home.index');
Route::prefix('operation')->group(function() {
    Route::get('/success',[HomeController::class,'success'])->name('web.operation.success');
});
