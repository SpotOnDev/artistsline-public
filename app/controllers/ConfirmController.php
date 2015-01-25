<?php

use Mailers\UserMailer as Mailer;

class ConfirmController extends \BaseController {


	public function __construct(Mailer $mailer)
	{
		$this->beforeFilter('emptyCart', array('on' => 'get'));
		$this->mailer = $mailer;
	}
	public function __destruct()
	{
		Cart::where('user_session_id', Session::getId())->delete();
		Shopper::find(Session::get('shopper_id'))->delete();
		Session::flush();
		Session::regenerate();
	}

	public function index()
	{
		$data = [
			'cart' => serialize(Cart::with('products')->where('user_session_id', Session::getId())->get()),
			'order_id' => Session::get('order_id'),
			'tracking_numbers' => Session::get('tracking_numbers'),
			'total' => Session::get('total'),
			'package_amount' => Session::get('package_amount'),
			'shipping_amount' => Session::get('shipping_amount')
		];
		$user = Customer::find(Session::get('customer_id'));;
		$this->mailer->confirm($user, $data);
		return View::make('confirm/confirmation')->with('order_id', Session::get('order_id'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
