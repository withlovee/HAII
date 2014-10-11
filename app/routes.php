<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index')->before('auth');
Route::get('errorlog', function(){
	return Redirect::to('errorlog/unmarked');
})->before('auth');
Route::get('errorlog/{status}', 'ErrorLogController@index')->before('auth');
Route::get('dailyop', function(){
	return Redirect::to('dailyop/unmarked');
})->before('auth');
Route::get('dailyop/{status}', 'DailyOpController@index')->before('auth');
Route::get('api/problems/all', 'APIProblemsController@all')->before('auth');
Route::get('api/problems/get', 'APIProblemsController@get')->before('auth');
Route::post('api/problems/update_status', 'APIProblemsController@updateStatus')->before('auth');
// Route::get('/', function()
// {
// 	return View::make('hello');
// });
//

// Confide routes
Route::get('users', 'UsersController@index')->before('admin-auth');
Route::get('users/create', 'UsersController@create')->before('admin-auth');
Route::post('users', 'UsersController@store');
Route::get('login', 'UsersController@login');
Route::post('users/login', 'UsersController@doLogin');
Route::get('users/edit/{id}', 'UsersController@edit')->before('admin-auth');
Route::post('users/update/{id}', 'UsersController@update')->before('admin-auth');
Route::get('users/destroy/{id}', 'UsersController@destroy')->before('admin-auth');
Route::get('users/profile', 'UsersController@profile')->before('auth');
Route::post('users/profile', 'UsersController@doProfile')->before('auth');
Route::get('users/confirm/{code}', 'UsersController@confirm');
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');
