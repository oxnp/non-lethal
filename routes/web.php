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




Route::group(['prefix'=> 'admin','middleware' => ['admin']], function () {

    //teplates email
    Route::resource('emails-templates','AdminPanel\EmailsTemplatesController');
    //teplates email

    //subscriber
    Route::resource('subscribers','AdminPanel\SubscribeController');
    Route::resource('newsletters','AdminPanel\SubscribeNewsletterController');
    Route::post('newsletter-send/{id}','AdminPanel\SubscribeNewsletterController@sendMessage')->name('newsletterSend');
    //subscriber

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
    //contents

});

//Front


Route::group(['prefix' => LocaleMiddleware::getLocale()],function(){
    Auth::routes();

    //subscribe
    Route::post('newsletter-send-mail','Front\SubscribeController@sendMSubscribeMail')->name('newsletterSendFront');
    //subscribe

    //mainpage
    Route::get('/','Front\HomeController@index')->name('index');
    //mainpage

    //paddle checkout
    Route::post('paddle-gateway','Front\PaddleCheckoutController@paddle_gateway')->name('paddle_gateway');
    //paddle checkout

    //subscriber
    Route::resource('subscriber','Front\SubscribersCategoriesController');
    //subscriber

    //download
    Route::get('/nlalib/download.php','Front\DownloadController@getFile');
    //download

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
    Route::get('/support/get-in-touch','Front\GetInTouchController@index')->name('support');

    //profile
    Route::get('/profile','Front\ProfileController@profile')->name('profile');
    Route::put('/profile','Front\ProfileController@updateProfile')->name('profile-update');

    Route::get('/my-licenses','Front\MyLicensesController@index')->name('my-licenses');

    Route::post('/user-notes','Front\MyLicensesController@updateUserNotes')->name('user-notes');

    Route::get('/queue-cancel-subscription','Front\MyLicensesController@queueCancelSubscription')->name('queueCancelSubscription');
    Route::post('/get-product-published-state','Front\MyLicensesController@getProductPublishedState')->name('getProductPublishedState');

    Route::post('/fulfillment','Front\MyLicensesController@fulfillment')->name('fulfillment');
    //profile

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

