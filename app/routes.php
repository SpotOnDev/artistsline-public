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

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

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

Route::get('paypal_test', function()
{
	$paypal_config = Config::get('paypal');
	$_api_context = new ApiContext(new OAuthTokenCredential($paypal_config['client_id'], $paypal_config['secret']));
	$_api_context->setConfig($paypal_config['settings']);

	$payer = new Payer();
	$payer->setPaymentMethod("paypal");
	$shipping = 1.00;if(cartTotal() > 3000) $shipping = 0;


	$item = new Item();
	$item->setName('brush')
		->setCurrency('USD')
		->setQuantity(1)
		->setPrice(15.00);

	$itemList = new ItemList();
	$itemList->setItems([$item]);

	$details = new Details();
	$details->setShipping($shipping)
		->setSubtotal(15.00);

	$amount = new Amount();
	$amount->setCurrency("USD")
		->setTotal(16.00)
		->setDetails($details);

	$transaction = new Transaction();
	$transaction->setAmount($amount)
		->setItemList($itemList)
		->setDescription("Payment description")
		->setInvoiceNumber(uniqid());

	$redirect_urls = new RedirectUrls();
	$redirect_urls->setReturnUrl(URL::route('paypal_review.index'))
		->setCancelUrl(URL::route('cart.index'));

	$payment = new Payment();
	$payment->setIntent("sale")
		->setPayer($payer)
		->setRedirectUrls($redirect_urls)
		->setTransactions([$transaction]);

	$payment->create($_api_context);

	foreach($payment->getLinks() as $link)
	{
		if($link->getRel() == 'approval_url')
		{
			$redirect_url  = $link->getHref();
			break;
		}
	}

	return Redirect::away($redirect_url);
});