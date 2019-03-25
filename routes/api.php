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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');
Route::get('/users', 'UserController@listUser');
Route::get('/users/{id}', 'UserController@detailUser');
Route::post('/search', 'UserController@search');
Route::post('/import', 'UserController@importExcel');	
Route::put('/users', 'UserController@changePassword');