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
    // Apis
    Route::get("/get-report", "ReportController@selectReport");
    Route::get("/get-users", "ReportController@getUsers");

});