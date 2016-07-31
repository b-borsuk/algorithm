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

Route::get('/', 'AlgoController@getHome');

Route::get('/biosystems', ['as' => 'biosystems', 'uses' => 'AlgoController@getBiosystems']);
Route::post('/biosystems', 'AlgoController@postBiosystems');

Route::get('/sugar', ['as' => 'sugar', 'uses' => 'AlgoController@getSugar']);
Route::post('/sugar', 'AlgoController@postSugar');

Route::get('/dynamic', ['as' => 'dynamic', 'uses' => 'AlgoController@getDynamic']);
Route::post('/dynamic', 'AlgoController@postDynamic');
