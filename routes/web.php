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

Route::get('/', "Controller@viewVue");
Route::get("/reportToJson", "ReportController@doReport");
Route::get("/usersToJson", "ReportController@getUsers");

//Views
Route::get("/report", "Controller@viewVue");
Route::get('/home', 'Controller@viewVue')->name('home');
Route::get("/user-profile", "Controller@viewVue");
//Logic
Route::get("/reportJson", "ReportController@selectReport");
Route::get("/export", "ReportController@export");

//Others
Auth::routes();