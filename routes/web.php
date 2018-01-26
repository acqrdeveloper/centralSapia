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

Route::get("/report", "Controller@viewVue");
Route::get("/user-profile", "Controller@viewVue");

Route::get("/reportToJson", "ReportController@doReport");

Route::get('/home', 'Controller@viewVue')->name('home');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
