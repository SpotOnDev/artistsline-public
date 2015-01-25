<?php
App::bind('Billing\BillingInterface', 'Billing\StripeBilling');
App::bind('Shipping\ShippingInterface', 'Shipping\EasyPostShipping');
class ReviewController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('noToken', array('on' => 'get'));
		$this->beforeFilter('emptyCart', array('on' => 'get'));
	}

	public function index()
	{
		$bill = App::make('Billing\BillingInterface');
		$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
		$shopper = Shopper::find(Session::get('shopper_id'));
		$shipping = calculateShipping($cart_contents) * SHIP_RATE;
		if(cartTotal($cart_contents) > 3000) $shipping = 0;
		return View::make('checkout/review', array('shopper' => $shopper, 'shipping' => $shipping, 'billing' => $bill->getInfo(Session::get('stripe_token'))->card, 'cart_contents' => $cart_contents, 'total' => 0, 'i' => 0));

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
		$bill = App::make('Billing\BillingInterface');
		$billing_info = $bill->getInfo(Session::get('stripe_token'))->card;

		$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
		$shipping_amount = calculateShipping($cart_contents) * SHIP_RATE;
		$total = cartTotal($cart_contents);
		if($total > 3000) $shipping_amount = 0;
		$package_amount = calculateShipping($cart_contents);

		if(Input::get('agree_terms') == 'Y')
		{
			$shopper = Shopper::find(Session::get('shopper_id'));
			$customer = new Customer;
			$customer->email = $shopper->email;
			$customer->first_name = $shopper->first_name;
			$customer->last_name = $shopper->last_name;
			$customer->address1 = $shopper->address1;
			$customer->address2 = $shopper->address2;
			$customer->city = $shopper->city;
			$customer->state = $shopper->state;
			$customer->zip = $shopper->zip;
			$customer->phone = $shopper->phone;
			$customer->save();
			$customer_id = $customer->id;

			$order = new Order;
			$order->customer_id = $shopper->id;
			$order->total = $total;
			$order->shipping = $shipping_amount;
			$order->credit_card_number = $billing_info->last4;
			$order->save();
			$order_id = $order->id;

			foreach($cart_contents as $item)
			{
				$order_content = new OrderContent;
				$order_content->order_id = $order_id;
				$order_content->product_id = $item->product_id;
				$order_content->quantity = $item->quantity;
				$order_content->price_per = $item->products['price'];
				$order_content->save();
			}

			$billing = App::make('Billing\BillingInterface');
			try
			{
				$charge = $billing->charge([
					'total' => $order->shipping + $order->total,
					'order_id' => $order_id,
					'token' => Session::get('stripe_token')
				]);
			}
			catch(Stripe_InvalidRequestError $e)
			{
				Session::flush();
				Session::regenerate();
				return Redirect::to('/');
			}

			catch(Stripe_CardError $e)
			{
				OrderContent::where('order_id', $order_id)->delete();
				Order::find($order_id)->delete();
				Customer::find($customer_id)->delete();
				return Redirect::refresh()->withFlashMessage($e->getMessage());
			}

			if($charge->paid)
			{
				$shipping = App::make('Shipping\ShippingInterface');
				$tracking = $shipping->create([
					'name' => $shopper->first_name . ' ' . $shopper->last_name,
					'address' => $shopper->address1,
					'address2' => $shopper->address2,
					'city' => $shopper->city,
					'state' => $shopper->state,
					'zip' => $shopper->zip
				], $cart_contents);
				$tracking_numbers = null;
				foreach ($tracking as $number){
					$tracking_numbers .= '  ' . $number;
				}

				Session::flash('order_id', $order_id);
				Session::flash('shipping_amount', $shipping_amount);
				Session::flash('customer_id', $customer_id);
				Session::flash('tracking_numbers', $tracking_numbers);
				Session::flash('total', $total);
				Session::flash('package_amount', $package_amount);
				return Redirect::action('ConfirmController@index');
			}
		}
		return Redirect::refresh();
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
