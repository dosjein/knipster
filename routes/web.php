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


//do grouping @todo @fix
Route::any('/customer/create' , 'CustomerApiController@create');
Route::any('/customer/edit' , 'CustomerApiController@edit');

Route::any('/money/balance' , 'MoneyApiController@deposit');
Route::any('/money/deposit' , 'MoneyApiController@deposit');
Route::any('/money/widraw' , 'MoneyApiController@widraw');
