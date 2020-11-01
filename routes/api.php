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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => 'auth.api'], function () {

    Route::get('/contacts', 'ContactController@getContacts');
    Route::get('/contact/id/{id}', 'ContactController@getContactById')->where('id', '[0-9]+');
    Route::post('/contact/add', 'ContactController@setContact');
    Route::delete('/contact/delete/{id}', 'ContactController@deleteContact')->where('id', '[0-9]+');
    Route::put('/contact/edit/{id}', 'ContactController@editContact')->where('id', '[0-9]+');


    Route::get('/companies', 'CompanyController@getCompanies');
    Route::get('/companies/all', 'CompanyController@getCompaniesAll');
    Route::post('/company/add', 'CompanyController@setCompany');
    Route::delete('/company/delete/{id}', 'CompanyController@deleteCompany')->where('id', '[0-9]+');
    Route::put('/company/edit/{id}', 'CompanyController@editCompany')->where('id', '[0-9]+');

});
