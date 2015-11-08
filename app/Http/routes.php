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



Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'PagesController@index');

    //Station routes
    Route::get('stations/{id}.json', 'StationsController@jsonShow');
    Route::get('stations.json', 'StationsController@jsonIndex');
    Route::get('stations/measurements/{id}', 'StationsController@showMeasurements');
    Route::get('stations/{id}', 'StationsController@show');
    Route::get('stations', 'StationsController@index');

    //Measurements routes
    Route::get('measurements/{id}', 'MeasurementsController@show');
    Route::get('measurements', 'MeasurementsController@index');
    Route::get('kyoto-longitude', 'MeasurementsController@kyotoLongitude');
    Route::get('temperatures', 'MeasurementsController@top10');

    //Rainfall map routes
    Route::get('world', 'RainfallController@index');
    Route::get('measurements.json', 'RainfallController@showPerStation');
    Route::get('rainmostrecent','RainfallController@showMostRecent');

    //Export routes
    Route::get('download','DownloadController@index');
    Route::get('downloadCount', 'DownloadController@count');

    //Administrator panel routes...
    Route::get('admin','Admin\AdminPanelController@show');
    Route::get('admin/create_user','Admin\AdminPanelController@showCreateUser');
    Route::get('admin/create','Admin\AdminPanelController@showUser');
    Route::post('admin/create','Admin\AdminPanelController@createUser');
});


// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

