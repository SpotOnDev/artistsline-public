<?php

App::bind('Billing\BillingInterface', 'Billing\PayPalBilling');
App::bind('Shipping\ShippingInterface', 'Shipping\EasyPostShipping');
class PayPalReviewController extends \BaseController {

    public function __construct(\Billing\BillingInterface $billing)
    {
        $this->beforeFilter('emptyCart', ['on' => 'get']);
        $this->beforeFilter('invalidPayPalRequest', ['on' => 'get']);
		$this->beforeFilter('emptyCart', ['on' => 'get']);
        $this->billing = $billing;
    }

    public function index()
    {
        $cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
        $shipping = calculateShipping($cart_contents) * SHIP_RATE;
        if (cartTotal($cart_contents) > 3000) $shipping = 0;
        Session::flash('payer_id', Input::get('PayerID'));
        $payment_id = Session::get('paypal_payment_id');
        try
        {
            $payment = $this->billing->retrieveCustomer(['token' => $payment_id]);
        }
        catch(Exception $e)
        {
            return Redirect::to('/');
        }
        return View::make('checkout/review_paypal', ['page_title' => 'Review Order', 'review_header' => 'class="active"', 'shopper' => $payment, 'shipping' => $shipping, 'cart_contents' => $cart_contents, 'total' => 0, 'i' => 0]);
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
        $payment_id = Session::get('paypal_payment_id');
        $shopper = $this->billing->retrieveCustomer(['token' => $payment_id]);

        if(Input::get('agree_terms') == 'Y')
        {
            $customer = new Customer;
            $customer->email = $shopper->email;
            $customer->first_name = $shopper->first_name;
            $customer->last_name = $shopper->last_name;
            $customer->address1 = $shopper->shipping_address->line1;
            $customer->address2 = $shopper->shipping_address->line2;
            $customer->city = $shopper->shipping_address->city;
            $customer->state = $shopper->shipping_address->state;
            $customer->zip = $shopper->shipping_address->postal_code;
            $customer->save();
            $customer_id = $customer->id;

            $order = new Order;
            $order->customer_id = $customer_id;
            $order->total = $total;
            $order->shipping = $shipping_amount;
            $order->type = 'paypal';
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
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'address' => $customer->address1,
                    'address2' => $customer->address2,
                    'city' => $customer->city,
                    'state' => $customer->state,
                    'zip' => $customer->zip
                ], $cart_contents);
            }
            catch(Exception $e)
            {
                OrderContent::where('order_id', $order_id)->delete();
                Order::find($order_id)->delete();
                Customer::find($customer_id)->delete();
                return Redirect::back()->withFlashMessage('There was an error in our system. Please try again in a few minutes.');
            }

            try
            {
                $payer_id = Session::get('payer_id');
                $result = $this->billing->charge(['token' => $payment_id, 'payer_id' => $payer_id]);
            }
            catch(Exception $e)
            {
                OrderContent::where('order_id', $order_id)->delete();
                Order::find($order_id)->delete();
                Customer::find($customer_id)->delete();
                return Redirect::back()->withFlashMessage($e->getMessage());
            }

            if($result->state == 'approved')
            {
                $order->provider_id = $result->id;
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
        return Redirect::back();
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
