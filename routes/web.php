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


Route::get('/stripe/{id}', 'SellerController@redirectToStripe')->name('redirect.stripe');
Route::get('/connect/{token}', 'SellerController@saveStripeAccount')->name('save.stripe');

Route::group([
    "prefix" => '{locale}',
    'where' => ['locale' => '(en||fr||de)']
], function ($language) {
    Route::get('/', 'HomeController')->name('home');
//    Route::view('/litige', 'litige');
//    Route::view('/mentions-legales', 'legales-notice');
//    Route::view('/conditions-generales-d-utilisation', 'cgu');
//    Route::view('/politique-de-confidentialite', 'privacy-policy');
//    Route::view('/contact', 'contact');
//    Route::view('/a-propos-de-nous', 'apropos');
//    Route::view('/renovation', 'renovation');
//Route::view('/je-vends-des-pieces', 'sell')->name('sell');
    Route::get('/research/{id}', 'ResearchController@showPublic');
    Route::get('/product/{id}', 'ProductController@show')->name('product');
    //Route::post('/checkout', 'CheckoutController@index')->name('checkout');


    Route::get('/product/{id}/checkout', 'CheckoutController@index')->name('checkout.get');
    Route::post('/get-regions', 'CheckoutController@getRegions')->name('get-regions');
    Route::post('/product/checkout', 'CheckoutController@checkoutProduct')->name('checkout.post');

    Route::post('/payment-form', 'CheckoutController@paymentForm')->name('checkout.cart');
    Route::get('/shipping-price', 'CheckoutController@shippingPrice')->name('checkout.shipping');

    //Route::post('/charge', 'CheckoutController@charge')->name('payment');
    Route::post('/charge', 'CheckoutController@charge')->name('payment');
    Route::get('/result', 'CheckoutController@result')->name('result');

});
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api|images|storage|litiges|product\/)[\/\w\.-]*');
