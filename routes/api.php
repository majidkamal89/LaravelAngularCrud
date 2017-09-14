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

// Company Routes
Route::get('company/list', 'CompanyController@loadData')->middleware('api');
Route::post('company/save', 'CompanyController@store')->middleware('api');
Route::get('company/delete/{id}', 'CompanyController@destroy')->middleware('api');
