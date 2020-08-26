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
    Route::get( 'user-session-info', 'UsersController@sessionInfoPage' );
    
    Route::prefix('subusers')->group(function() {
        Route::get( '/', 'UsersController@index' );
        Route::post( '/', 'UsersController@ajaxDatatable' );
    
        Route::get( 'ajax-subuser-modal/{id_subuser?}', 'UsersController@ajaxAddSubuserModal' );
        Route::post( 'add', 'UsersController@ajaxAddSubuser' );
        Route::post( 'edit', 'UsersController@ajaxEditSubuser' );
        Route::post( 'remove/{id_subuser}', 'UsersController@ajaxRemoveSubuser' );

        Route::get( 'ajax-modal-attach-socials/{id_subuser}', 'UsersController@modalAttachSocials' );
        Route::post( 'ajax-attach-socials', 'UsersController@ajaxAttachSocials' );
        
    });
});

Route::post('check-user', 'AuthController@getUsersByEmail');

Route::get('social-auth/{social}/{id_user}/{user_type}', 'AuthController@redirectToProvider');
Route::get('social-response/{vk}', 'AuthController@handleProviderCallback');

Route::get('test-view', function () {
    return '';
});

//  Route::post( 'subusers/ajax-add-subuser', 'UsersController@ajaxAddSubuser' )->middleware('ajax');

