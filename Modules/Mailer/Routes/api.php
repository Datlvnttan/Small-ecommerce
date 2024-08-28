<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Mailer\Http\Controllers\Api\MailApiController;

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

Route::group(["prefix"=>"mail"],function(){
    Route::post("{id}/resend-email-verify",[
        MailApiController::class,
        "resendEmailVerify"
    ])->name("api.resend-email-verify");
    Route::post("{id}/verify",[
        MailApiController::class,
        "verify"
    ])->name("api.email-verify");
});