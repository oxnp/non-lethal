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

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['admin']], function () {

Route::get('admin','AdminPanel\AdminPanelController@index');

Route::resource('buyers','AdminPanel\BuyersController');
Route::resource('products','AdminPanel\ProductsController');


Route::resource('precodes','AdminPanel\PrecodeController');
Route::resource('ilok_codes','AdminPanel\IlokCodesController');



Route::post('feature_precode','AdminPanel\PrecodeController@generateFeaturePreCodeAJAX')->name('generateFeaturePreCodeAJAX');
Route::post('pre_activation_code','AdminPanel\PrecodeController@generate')->name('generatePreActivationCodes');
Route::get('export_precodes','AdminPanel\PrecodeController@exportPrecodes')->name('exportPrecodes');
Route::get('purge_precodes','AdminPanel\PrecodeController@purgeEmpty')->name('purgeEmpty');




});
