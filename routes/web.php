<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('sign-in', 'AuthController@authentication');
Route::post('sign-in', 'AuthController@ajaxAuthentication');
Route::post('sign-out', 'AuthController@ajaxLogout');

Route::get('sign-up', 'UsersController@registration');
Route::post('sign-up', 'UsersController@ajaxRegistration');

Route::group( [ 'namespace' => 'Profile','prefix' => 'profile' ], function(){
    Route::get( '/', 'UsersController@index' );
});