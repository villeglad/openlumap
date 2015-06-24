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

$app->get('/', function () use ($app) {
    return view('map.index');
});
$app->get('api/companies', 'App\Http\Controllers\CompaniesController@index' );
     
$app->get('api/{id}', 'App\Http\Controllers\CompaniesController@show' );
$app->get('api/find', 'App\Http\Controllers\CompaniesController@find' );
$app->post('api/companies','App\Http\Controllers\CompaniesController@create');
$app->put('api/companies/{id}','App\Http\Controllers\CompaniesController@update');
$app->delete('api/companies/{id}','App\Http\Controllers\CompaniesController@delete');
