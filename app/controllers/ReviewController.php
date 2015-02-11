<?php
use \PayPal\Api\Payment;
use \PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

App::bind('Billing\BillingInterface', 'Billing\StripeBilling');
App::bind('Shipping\ShippingInterface', 'Shipping\EasyPostShipping');
class ReviewController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('emptyCart', ['on' => 'get']);
		$this->beforeFilter('noToken', ['on' => 'get']);
		$this->beforeFilter('secureRequest', ['on' => 'get']);
	}

	public function index()
	{
		$bill = App::make('Billing\BillingInterface');
		$billing_info = $bill->retrieveCustomer(['token' => Session::get('stripe_token')])->card;
		$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
		$shopper = Shopper::find(Session::get('shopper_id'));
		$shipping = calculateShipping($cart_contents) * SHIP_RATE;
		if (cartTotal($cart_contents) > 3000) $shipping = 0;
		return View::make('checkout/review', ['page_title' => 'Review Order', 'review_header' => 'class="active"', 'shopper' => $shopper, 'shipping' => $shipping, 'billing' => $billing_info, 'cart_contents' => $cart_contents, 'total' => 0, 'i' => 0]);
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
			$order->customer_id = $customer_id;
			$order->total = $total;
			$order->shipping = $shipping_amount;
			$order->type = 'stripe';
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
			try {
				$shipping = App::make('Shipping\ShippingInterface');
				$shipment = $shipping->create([
					'name' => $shopper->first_name . ' ' . $shopper->last_name,
					'address' => $shopper->address1,
					'address2' => $shopper->address2,
					'city' => $shopper->city,
					'state' => $shopper->state,
					'zip' => $shopper->zip
				], $cart_contents);
			}
			catch(Exception $e)
			{
				OrderContent::where('order_id', $order_id)->delete();
				Order::find($order_id)->delete();
				Customer::find($customer_id)->delete();
				return Redirect::refresh()->withFlashMessage('There was an error in our system. Please try again in a few minutes.');
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
				$order->provider_id = $charge->id;
				$order->save();
				$tracking_numbers = null;
				$n = 1;
				foreach ($shipment as $package){
					$tracking_numbers .= '  ' . $package->tracking_code;
					$tracking[$n] = $package->tracking_code;
					$trk_id[$n] = $package->tracker->id;
					$n++;
				}
				$n = 1;
				$pack = packageProducts($cart_contents);
				foreach($pack as $pkg)
				{
					$i = 1;

					while($i < 5)
					{
						if (isset($pkg[$i]))
						{
							$box = new Package;
							$box->order_id = $order_id;
							$box->trk_id = $trk_id[$n];
							$box->tracking = $tracking[$n];
							$box->product_id = $i;
							$box->quantity = $pkg[$i];
							$box->save();
						}
						$i++;
					}
					$n++;
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
