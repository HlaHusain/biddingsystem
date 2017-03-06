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
Route::get('/', function() {
    return View::make('index');
});
Route::group(array('prefix'=>'api/v1'),function(){
    Route::get('products/checkTime','mainController@checkTime');
  //  Route::resource('user','userController');
    Route::post('user/login','userController@login');
    Route::get('user/login','userController@login');
    Route::get('products/addProduct','mainController@create');

Route::resource('product','productController');
Route::resource('transaction','transactionController');
Route::resource('bid','bidController');

       Route::get('products/runningbids','mainController@runningbids');
    Route::get('products/myclosedbids','mainController@myclosedbids');
     Route::get('products/closedbids','mainController@closedbids');
Route::get('products/mybids','mainController@mybids');
Route::get('products/Uindex','mainController@Uindex');
Route::post('products/addProduct','mainController@create');
Route::get('products','mainController@index');
Route::get('products/{id}','mainController@show');
Route::get('products/editProduct/{id}','mainController@update');
Route::post('products/editProduct/{id}','mainController@update');
Route::get('products/deleteProduct/{id}','mainController@delete');
Route::post('products/bidProduct/{product_id}/{bid_id}','mainController@bidProduct');
});


