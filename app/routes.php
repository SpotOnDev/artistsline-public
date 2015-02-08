<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('pages/home');
});

Route::get('review', [
	'uses' => 'ReviewController@index',
	'as' => 'review.index'
]);
Route::post('review', [
	'uses' => 'ReviewController@store',
	'as' => 'review.store'
]);
Route::post('review_paypal', [
	'uses' => 'ReviewController@store_paypal',
	'as' => 'review.paypal'
]);

Route::get('about', function()
{
	return View::make('pages/about');
});

Route::get('return', function()
{
	return View::make('pages/return_policy');
});

Route::get('privacy', function()
{
	return View::make('pages/privacy_policy');
});

Route::get('contact', [
	'uses' => 'ContactController@index',
	'as' => 'pages.contact'
]);

Route::post('contact', [
	'uses' => 'ContactController@store'
]);

Route::get('products', function()
{
	return View::make('pages/products');
});

Route::get('products/{product}', function($product)
{
	return View::make('products/'. $product)
		->with('product', $product);
});

Route::put('cart/add/{product}', [
	'uses' => 'CartController@add',
	'as' => 'cart.add'
]);

Route::patch('cart', [
	'uses' => 'CartController@update',
	'as' => 'cart.update'
]);

Route::get('cart/delete/{product_id}', [
	'uses' => 'CartController@destroy',
	'as' => 'cart.delete'
]);

Route::get('cart', [
	'uses' => 'CartController@index',
	'as' => 'cart.index'
]);

Route::get('checkout', 'ShippingController@index');

Route::post('tracking', 'WebhookController@packageShipped');

Route::get('payment', [
	'uses' => 'CartController@postPayment',
	'as' => 'cart.paypal_payment'
]);

Route::resource('paypal_review', 'PayPalReviewController');
Route::resource('shipping', 'ShippingController');
Route::resource('billing', 'BillingController');
Route::resource('confirm', 'ConfirmController');