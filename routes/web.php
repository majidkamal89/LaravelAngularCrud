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


Auth::routes();
Route::group(['prefix' => '/', 'middleware' => 'auth'], function (){
    Route::get('/', 'HomeController@index')->name('home');

    Route::group(['prefix' => '/', 'middleware' => 'supperAdmin'], function(){
        Route::get('companies', 'CompanyController@index')->name('company');
        Route::get('/create/user/{id}', 'UserController@create')->name('createUser');
        Route::get('/edit/user/{id}', 'UserController@edit')->name('editUser');
    });

    Route::post('/create/user/save', 'UserController@store')->name('storeUser');
    Route::group(['middleware' => 'impersonate'], function()
    {
        Route::group(['middleware' => 'Manager'], function(){
            // For Impersonate user
            Route::get('/manager', 'HomeController@index');
            Route::get('/user', 'HomeController@index')->name('user');

            // Customer Routes
            Route::group(['prefix' => '/'], function(){
                Route::get('customers', 'CustomerController@index')->name('customers');
                Route::get('customer/list', 'CustomerController@loadData')->name('listCustomers');
                Route::post('customer/save', 'CustomerController@store');
                Route::get('customer/delete/{id}', 'CustomerController@destroy');
            });
            //
            // Project Routes
            Route::group(['prefix' => 'project'], function(){
                Route::get('/{customer_id}', 'ProjectController@index')->name('listProjects');
                Route::get('/detail/{id}', 'ProjectController@show')->name('projectDetail');
                Route::post('/save', 'ProjectController@store')->name('storeProject');
                Route::get('/delete/{id}', 'ProjectController@destroy');
            });
            //
            // Settings page Routes
            Route::group(['prefix' => 'settings'], function(){
                // Driver Routes
                Route::get('/user', 'UserController@index')->name('listDrivers');
                Route::get('/user/list', 'UserController@getAllUser')->name('getAllDriver');
                Route::get('/user/delete/{id}', 'UserController@destroy');

                //Setting - Company route
                Route::get('/company', 'CompanyController@showCompany')->name('getCompany');
                Route::get('/company/list', 'CompanyController@loadData')->name('getUserCompany');
                Route::post('/company/save', 'CompanyController@store');
            });

        });

    });

    // Login As Routes
    Route::get('/users/impersonate/{id}', 'UserController@impersonate');
    Route::get('/users/stop/{user}', 'UserController@stopImpersonate');
});
Route::get('/', 'HomeController@index');
