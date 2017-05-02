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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/profile', 'ProfileController@index');
Route::post('/api/notification/send', 'NotificationController@send');
Route::post('/api/notification/register_profile', 'NotificationController@registerProfile');
Route::post('/api/notification/add_time', 'NotificationController@addTime');
Route::post('/api/notification/remove_time', 'NotificationController@removeTime');
