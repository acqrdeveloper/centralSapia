<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['verify.apitoken:api']], function () {

    Route::get("/get-report", "ReportController@doReport");
    Route::get("/get-users", "ReportController@getUsers");
    Route::get("/select-report", "ReportController@selectReport");

});