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

Route::group( [ 'namespace' => 'Profile', 'prefix' => 'profile' ], function(){

    Route::prefix('subusers')->group(function() {
        Route::get( '/', 'UsersController@index' );
        Route::post( '/', 'UsersController@ajaxDatatable' );
        
        Route::get( 'ajax-add-subuser-modal', 'UsersController@ajaxAddSubuserModal' );
        Route::post( 'ajax-add-subuser', 'UsersController@ajaxAddSubuser' );
    });
    
    
});

Route::get('social-auth/{type}', 'AuthController@redirectToProvider');
Route::get('social-response/{vk}', 'AuthController@handleProviderCallback');

Route::get('test-view', function () {
    return '';
});

//  Route::post( 'subusers/ajax-add-subuser', 'UsersController@ajaxAddSubuser' )->middleware('ajax');

