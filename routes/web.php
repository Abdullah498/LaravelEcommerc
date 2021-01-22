<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/',function(){
    return redirect(route('login'));
});

Route::group(['prefix'=>'admin','namespace'=>'admin','middleware'=>['auth','verified']],function(){

    Route::get('dashboard',function(){
        return view('en.admin.dashboard.index');
    });

    Route::group(['prefix'=>'user','namespace'=>'user'],function(){
        
    });
    Route::group(['prefix'=>'product','namespace'=>'product'],function(){
        Route::get('all-products' , 'productController@index');
        Route::get('edit-product/{product_id}' , 'productController@edit');
        Route::get('add-product','productController@add');
        Route::post('create','productController@create');
        Route::put('update','productController@update');
        Route::delete('delete','productController@delete');
    });   
});
Route::group(['prefix'=>'user','namespace'=>'user'],function(){

});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
