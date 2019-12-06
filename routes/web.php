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

use App\Http\Models\Front\Contents\ProductsPageCategory;

Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['prefix'=> 'admin','middleware' => ['admin']], function () {

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


    //contents
    Route::resource('static-pages','AdminPanel\StaticPagesController');
    Route::resource('products-pages','AdminPanel\ProductsPageController');
    Route::resource('products-pages-categories','AdminPanel\ProductsPageCategoriesController');
    Route::resource('news','AdminPanel\NewsController');
    Route::resource('user-stories','AdminPanel\UserStoriesController');
    Route::resource('knowledge-base','AdminPanel\KnowledgeBaseController');
    Route::resource('knowledge-base-categories','AdminPanel\KnowledgeBaseCategoriesController');


});



//Front




//Route::get('/partners',function(){return view('Front.partners');})->name('partners');
Route::get('/company',function(){
    $categories = ProductsPageCategory::getCategoriesTolist();
    return view('Front.company')->with([
        'categories'=>$categories
    ]);

})->name('company');
Route::get('/impressum',function(){
    $categories = ProductsPageCategory::getCategoriesTolist();
    return view('Front.impressum')->with([
        'categories'=>$categories
    ]);
})->name('impressum');
Route::get('/disclaimer',function(){
    $categories = ProductsPageCategory::getCategoriesTolist();
    return view('Front.privacy_policy')->with([
        'categories'=>$categories
    ]);

})->name('privacy_policy');


Route::get('/support',function(){
    $categories = ProductsPageCategory::getCategoriesTolist();
    return view('Front.support')->with([
        'categories'=>$categories
    ]);

})->name('support');





Route::group(['prefix' => LocaleMiddleware::getLocale()],function(){

//mainpage
Route::get('/','Front\HomeController@index')->name('index');
//mainpage

//products
Route::get('/'.env('PRODUCTS_URL').'/{category}','Front\ProductController@category');
Route::get('/'.env('PRODUCTS_URL').'/{category}/{page}','Front\ProductController@page');
//products


//user stories
Route::get('/'.env('USER_STORIES_URL'),'Front\UserStoriesController@index');
Route::get('/'.env('USER_STORIES_URL').'/{stories}','Front\UserStoriesController@show');
//user stories

//news
Route::get('/'.env('NEWS_URL'),'Front\NewsController@index');
Route::get('/'.env('NEWS_URL').'/{news}','Front\NewsController@show');
//news

//static
Route::get('/{page}','Front\StaticPageController@page');
//static

//knowledge base
Route::get('/{headcategory}/{category}','Front\KnowledgeBaseController@category');
Route::get('/{headcategory}/{category}/{subcategory}','Front\KnowledgeBaseController@subcategory');
Route::get('/{headcategory}/{category}/{subcategory}/{item}','Front\KnowledgeBaseController@item');
//knowledge base



});






//Route::get('/home', 'HomeController@index')->name('home');



