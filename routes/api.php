<?php

use App\Http\Middleware\CheckIfUserIsAdmin;
use App\Models\Product;

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
Route::get('home/researches', 'HomeController@research');
Route::get('home/products', 'HomeController@product');
Route::post('litige', 'LitigeController@sendLitige');
Route::post('contact', 'LitigeController@sendContact');
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('refresh', 'AuthController@refresh')->name('refresh');
    Route::delete('logout', 'AuthController@logout')->name('logout');
    Route::delete('', 'AuthController@destroy');
    Route::post('updatePassword', 'AuthController@updatePassword')
        ->name('updatePassword');
});
Route::get('email-validation/{user}/{email}', 'AuthController@emailValidation')
    ->name('email-validation')->middleware('signed');
Route::post('password/email', 'ForgotPasswordController@forgot');
Route::post('password/reset', 'ForgotPasswordController@reset');
Route::post('password/token', 'ForgotPasswordController@tokenValid');
//WebHooks
Route::get('hookObvy', 'ObvyController@processHook');
//Route::group(['middleware' => ['auth:api']], function () {
Route::get('me', 'AuthController@me')->name('me')->middleware('auth:api');
Route::resource('user', 'AuthController');
Route::resource('pays', 'CountryController');
Route::resource('merchant', 'MerchantController');
//Researches
Route::resource('message', 'MessageController');
Route::resource('research', 'ResearchController')->middleware('auth:api');
Route::post('research/adminEdit/{research}', 'ResearchController@updateAdmin')
    ->middleware('auth:api', CheckIfUserIsAdmin::class);
Route::resource('research.product', 'Research\ProductController');
Route::resource('research.part', 'Research\PartController')
    ->middleware('auth:api', CheckIfUserIsAdmin::class);
Route::resource('product.part', 'Product\PartController')
    ->middleware('auth:api', CheckIfUserIsAdmin::class);

Route::resource('merchant.product', 'Merchant\ProductController')
    ->middleware('auth:api');
Route::post('updateProduct', 'Merchant\ProductController@updateProduct')
    ->middleware('auth:api');
Route::post('changeView', 'AuthController@changeViewSettings')
    ->middleware('auth:api');

Route::resource('modele', 'ModeleController');
Route::delete('modele/{modele}/{new_modele}', 'ModeleController@destroy')
    ->middleware(CheckIfUserIsAdmin::class);
Route::resource('brand', 'BrandController');
Route::delete('brand/{brand}/{new_brand}', 'BrandController@destroy')
    ->middleware(CheckIfUserIsAdmin::class);
Route::get('user/{user}/researchAdmin/{id}', 'User\ResearchController@showAdmin')
    ->middleware('auth:api', CheckIfUserIsAdmin::class);
Route::resource('brand.modele', 'Brand\ModeleController');
Route::resource('user.userModel', 'User\UserModelController')
    ->middleware('auth:api');
Route::resource('user.sales', 'User\SalesController')->middleware('auth:api');
Route::resource('user.research', 'User\ResearchController');
Route::resource('user.merchant', 'User\MerchantController');
Route::resource('user.research.products', 'User\Research\SaleController');
Route::resource('user.message', 'User\MessageController');
Route::get('messageCount/{userId}', 'User\MessageController@messageCount')
    ->middleware('auth:api');
Route::resource('user.notification', 'User\NotificationController')
    ->middleware('auth:api');
Route::group(['middleware' => [CheckIfUserIsAdmin::class]], function () {
    Route::resource('admin.researches', 'Admin\ResearchController');
    Route::post(
        'admin/{admin}/researches/{research}/reject',
        'Admin\ResearchController@reject'
    );
    Route::resource('admin.users', 'Admin\UserController');
    Route::resource('admin.alerts', 'Admin\AlertController');
    Route::resource('product', 'ProductController');
    Route::get('messageId/{id}', 'Admin\MessageController@getIdMessage');
    Route::get('productsToEdit', 'Admin\ProductController@productsToEdit');
    Route::resource('admin.message', 'Admin\MessageController');
    Route::resource('admin.product', 'Admin\ProductController');
    Route::post('admin/{admin}/product/{product}/reject', 'Admin\ProductController@reject');
    Route::get('admin/research/{research}/toggle-still', 'Admin\ResearchController@toggleStill');
});
Route::get('conv/{idMessage}', 'Admin\MessageController@getConv');
Route::resource('part', 'PartController');
Route::resource('category', 'CategoryController');
Route::post('toggleResearchAlert', 'ResearchAlertController@toggle');
Route::get('scriptImg', 'Merchant\ProductController@scriptImg');
//categories
//parts
Route::delete('part/{part}/{new_part}', 'PartController@destroy');
Route::resource('modele.category', 'Modele\CategoryController');
Route::resource('modele.compatible', 'Modele\ModeleController');
Route::resource('modele.category.part', 'Modele\Category\PartController');
Route::resource('modele.part', 'Modele\PartController');

//alerts
Route::resource(
    'merchant.researchAlert',
    'Merchant\AlertSubscriptionController'
)->middleware('auth:api');
Route::post('/merchant/{merchant}/researchAlert/batchDelete', 'Merchant\AlertSubscriptionController@batchDelete')
    ->middleware('auth:api');
Route::resource('merchant.sales', 'Merchant\SalesController')
    ->middleware('auth:api');


Route::get(
    'merchant/{merchant}/exportCatalog',
    'MerchantController@exportCatalog'
)->middleware('auth:api');


Route::get('orders/merchant/{id}', 'OrdersController@index')->middleware('auth:api');
Route::get('order/{id}', 'OrdersController@getOrderById')->middleware('auth:api');
Route::get('all-orders', 'OrdersController@allOrders')->middleware('auth:api');
Route::get('all-products', 'FeesController@getAllProducts')->middleware('auth:api');
Route::get('all-merchants', 'FeesController@getAllMerchants')->middleware('auth:api');
Route::get('all-pieces-status', 'FeesController@getAllPiecesStatus')->middleware('auth:api');


Route::get('seller/{id}', 'SellerController@showProfile')->middleware('auth:api');
//Route::get('seller/{id}', 'SellerController@showProfile')->name('seller.profile');


Route::post('charge/{id}', 'SellerController@purchase')->name('complete.purchase');

Route::get('all-fees', 'FeesController@getAllFees')->middleware('auth:api');
Route::post('add-fee', 'FeesController@AddFee')->middleware('auth:api');
Route::get('fee/{id}', 'FeesController@getFee')->middleware('auth:api');
Route::put('update-fee/{id}', 'FeesController@updateFee')->middleware('auth:api');
Route::delete('delete-fee/{id}', 'FeesController@deleteFee')->middleware('auth:api');

// Extend Research Duration
Route::get('extend-research-duration/{encryptedId}', 'Actions\ExtendResearchDurationController');
//get public models for front page
Route::get('public-models', 'Actions\PublicModelsController');

//notifications
Route::resource('notification', 'NotificationController');
//});

// AdminModifications
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/admin-modifications/validate/product/{product}', 'Actions\ValidateAdminChangesController@product');
    Route::post('/admin-modifications/validate/research/{research}', 'Actions\ValidateAdminChangesController@research');
});
Route::group(['middleware' => [CheckIfUserIsAdmin::class]], function () {
    Route::post('/admin-modifications/admin-edit/research/{research}',
        'Actions\AdminEditModelController@research');
    Route::post('/admin-modifications/admin-edit/product/{product}',
        'Actions\AdminEditModelController@product');
    Route::get('/toggle-public-status/product/{product}', 'Actions\SwitchPublicModelController@product');
    Route::get('/toggle-public-status/research/{research}', 'Actions\SwitchPublicModelController@research');
});



Route::get('test',function (){
    DB::table('products')->update(array('merchant_id' => 1));
});


