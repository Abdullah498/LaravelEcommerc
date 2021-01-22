<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix'=>'admin'],function(){
    Route::group(['prefix'=>'product' , 'namespace'=>'product' , 'middleware'=>'CheckToken'],function(){
        Route::get('all-products','productController@index');
        Route::post('add-product','productController@store');
        Route::post('update-product','productController@update');
        Route::delete('delete-product','productController@delete');
    });

    Route::group(['prefix'=>'user' , 'namespace'=>'auth'],function(){
        Route::post('register' , 'authController@register');
        Route::post('login' , 'authController@login');

        Route::group(['middleware'=>'CheckToken'],function(){
            Route::post('check-code' , 'authController@checkCode');
            Route::get('profile' , 'authController@profile');
            Route::post('send-code' , 'authController@sendCode');
            Route::post('update-profile','authController@updateProfile');
            Route::post('logout','authController@logout');
        });

    });
    
});
