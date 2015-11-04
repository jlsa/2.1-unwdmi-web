<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'auth', function() {
    return view('welcome');
}]);

Route::get('stations/{id}', 'StationsController@show');

Route::get('temperatures', [
    'middleware' => 'auth',
    'uses' => 'Top10TemperatureController@show'
]);

Route::get('all', [
    'middleware' => 'auth',
    'uses' => 'AllWeatherDataController@show'
]);

Route::get('rainperstation', [
    'middleware' => 'auth',
    'uses' => 'RainfallController@showPerStation'
]);

Route::get('rainmostrecent', [
    'middleware' => 'auth',
    'uses' => 'RainfallController@showMostRecent'
]);

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
