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
//Front
Route::get('/','Front\HomeController@index')->name('index');
Route::get('/partners',function(){return view('Front.partners');})->name('partners');
Route::get('/company',function(){return view('Front.company');})->name('company');
Route::get('/impressum',function(){return view('Front.impressum');})->name('impressum');
Route::get('/disclaimer',function(){return view('Front.privacy_policy');})->name('privacy_policy');
Route::get('/support',function(){return view('Front.support');})->name('support');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['admin']], function () {

Route::get('admin','AdminPanel\AdminPanelController@index');

Route::resource('buyers','AdminPanel\BuyersController');
Route::post('export_buyers','AdminPanel\BuyersController@export')->name('exportBuyers');

Route::resource('products','AdminPanel\ProductsController');


Route::resource('precodes','AdminPanel\PrecodeController');
Route::resource('ilok_codes','AdminPanel\IlokCodesController');
Route::post('remove_ilok_codes','AdminPanel\IlokCodesController@remove')->name('removeIlokCodes');
Route::post('import_ilok_codes','AdminPanel\IlokCodesController@import')->name('importIlokCodes');


Route::resource('licenses','AdminPanel\LicenseController');

Route::post('transfer_license','AdminPanel\LicenseController@transferLicense')->name('transferLicense');



Route::post('feature_precode','AdminPanel\PrecodeController@generateFeaturePreCodeAJAX')->name('generateFeaturePreCodeAJAX');
Route::post('pre_activation_code','AdminPanel\PrecodeController@generate')->name('generatePreActivationCodes');
Route::get('export_precodes','AdminPanel\PrecodeController@exportPrecodes')->name('exportPrecodes');
Route::get('purge_precodes','AdminPanel\PrecodeController@purgeEmpty')->name('purgeEmpty');






});
