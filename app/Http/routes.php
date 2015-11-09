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

Route::get('/', ['middleware' => 'auth', function () {
    return view('welcome');
}]);


Route::group(['middleware' => 'auth'], function () {
   Route::get('stations/{id}.json', 'StationsController@jsonShow');
   Route::get('stations.json', 'StationsController@jsonIndex');
   Route::get('stations/measurements/{id}', 'StationsController@showMeasurements');
   Route::get('stations/{id}', 'StationsController@show');
   Route::get('stations', 'StationsController@index');


   Route::get('measurements/{id}', 'MeasurementsController@show');
   Route::get('measurements', 'MeasurementsController@index');
   Route::get('kyoto-longitude', 'MeasurementsController@kyotoLongitude');
   Route::get('temperatures', 'MeasurementsController@top10');


   Route::get('world', 'RainfallController@index');
   Route::get('measurements.json', 'RainfallController@showPerStation');
   Route::get('rainmostrecent','RainfallController@showMostRecent');
});





Route::get('download', [
    'middleware' => 'auth',
    'uses' => 'DownloadController@index'
]);

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

//Administrator panel routes...
Route::get('admin', [
    'middleware' => 'auth',
    'uses' => 'Admin\AdminPanelController@show'
]);

Route::get('admin/create_user', [
    'middleware' => 'auth',
    'uses' => 'Admin\AdminPanelController@showCreateUser'
]);

Route::get('admin/create', [
    'middleware' => 'auth',
    'uses' => 'Admin\AdminPanelController@showUser'
]);

Route::post('admin/create', [
    'middleware' => 'auth',
    'uses' => 'Admin\AdminPanelController@createUser'
]);
