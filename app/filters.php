<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});

App::after(function($request, $response)
{
	//
});

Route::filter('secureRequest', function($request)
{
	if( ! Request::secure())
	{
		return Redirect::secure(Request::path());
	}
});

Route::filter('emptyCart', function()
{
	if(Cart::where('user_session_id', Session::getId())->count() < 1){
		return View::make('carts/empty_cart');
	}
});

Route::filter('invalidPayPalRequest', function()
{
	if (!Input::has('PayerID') || !Input::has('token')) {
		return Redirect::route('cart.index');
	}
});

Route::filter('billingCompleted', function()
{
	if(Session::has('billing_form')){
		if(Session::get('billing_form') == 'completed')
		return Redirect::to('review');
	}
});

Route::filter('billingUnset', function()
{
	if(Input::has('unset')){
		Session::forget('billing_form');
	}
});

Route::filter('noCustomerId', function()
{
	if (Session::has('customer_id'))
	{
		return Redirect::to('/');
	}
});

Route::filter('noToken', function()
{
	if(!Session::has('stripe_token') || !Session::has('paypal_payment_id')){
		return Redirect::to('cart');
	}
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
