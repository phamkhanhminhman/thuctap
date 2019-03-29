<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckToken;

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

Route::group(['middleware' => ['CheckToken']], function () {
    Route::post('/users', 'UserController@store');
	Route::put('/users/{id}', 'UserController@update');
	Route::delete('/users/{id}', 'UserController@destroy');
	Route::get('/users', 'UserController@listUser')->middleware('CheckToken');
	Route::get('/users?query={query}&page={page}&pageSize={pageSize}&sort={sort}', 'UserController@listUser');
	Route::get('/users/{id}', 'UserController@detailUser');
	Route::post('/search', 'UserController@search');
	Route::post('/import', 'UserController@importExcel');	
	Route::put('/users', 'UserController@changePassword');
	Route::get('/group', 'UserController@listGroup');
});
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');

