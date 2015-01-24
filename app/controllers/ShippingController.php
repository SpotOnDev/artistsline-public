<?php

class ShippingController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public $states = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming');

	public function __construct()
	{
		$this->beforeFilter('emptyCart', array('on' => 'get'));
	}

	public function index()
	{
		return View::make('checkout/shipping')->with('states', $this->states);
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
		App::bind('Shipping\ShippingInterface', 'Shipping\EasyPostShipping');

		$validation = Validator::make(Input::all(), Shopper::$rules);

		if($validation->fails())
		{
			return Redirect::back()->withInput()->withErrors($validation->messages());
		}

		$shipping = App::make('Shipping\ShippingInterface');
		try
		{
			$shipping->address([
				'address' => Input::get('address'),
				'address2' => Input::get('address2'),
				'zipcode' => Input::get('zipcide'),
				'city' => Input::get('city'),
				'state' => Input::get('state')
			]);
		}catch (Exception $e)
		{
			return Redirect::to('shipping')->with('easypost_error', $e->getMessage())->withInput();
		}

		if(Input::get('use') == 'Y') Input::flash();
		$shopper = new Shopper;
		$shopper->email = Input::get('email');
		$shopper->first_name = Input::get('first_name');
		$shopper->last_name = Input::get('last_name');
		$shopper->address1 = Input::get('address');
		$shopper->address2 = Input::get('address2');
		$shopper->city = Input::get('city');
		$shopper->state = Input::get('state');
		$shopper->zip = Input::get('zipcode');
		$shopper->phone = Input::get('phone');
		$shopper->save();

		Session::put('shopper_id', $shopper->id);

		return Redirect::action('BillingController@index');
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
