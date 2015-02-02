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
	$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
	return View::make('pages/home', array('cart_contents' => $cart_contents));
});

Route::get('about', function()
{
	$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
	return View::make('pages/about', array('cart_contents' => $cart_contents));
});

Route::get('return', function()
{
	$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
	return View::make('pages/return_policy', ['cart_contents' => $cart_contents]);
});

Route::get('privacy', function()
{
	$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
	return View::make('pages/privacy_policy', ['cart_contents' => $cart_contents]);
});

Route::get('contact', ['uses' => 'ContactController@index', 'as' => 'pages.contact']);
Route::post('contact', ['uses' => 'ContactController@store']);

Route::get('products', function()
{
	$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
	return View::make('pages/products', array('cart_contents' => $cart_contents));
});

Route::get('products/{product}', function($product)
{
	$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
	return View::make('products/'. $product, array('cart_contents' => $cart_contents))->with('product', $product);
});

Route::put('cart/add/{product}', array('uses' => 'CartController@add', 'as' => 'cart.add'));
Route::patch('cart', array('uses' => 'CartController@update', 'as' => 'cart.update'));
Route::get('cart/delete/{product_id}', array('uses' => 'CartController@destroy', 'as' => 'cart.delete'));
Route::get('cart', 'CartController@index');
Route::get('checkout', 'ShippingController@index');
Route::resource('shipping', 'ShippingController');
Route::resource('billing', 'BillingController');
Route::resource('review', 'ReviewController');
Route::resource('confirm', 'ConfirmController');
Route::post('tracking', 'WebhookController@packageShipped');