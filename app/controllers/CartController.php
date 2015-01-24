<?php

class CartController extends \BaseController {

	public function index()
	{
		if(!!Cart::with('products')->where('user_session_id', Session::getId())->count())
		{
			$cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
			$shipping = calculateShipping($cart_contents) * SHIP_RATE;
			if(cartTotal($cart_contents) > 3000) $shipping = 0;
			return View::make('carts/cart', array('cart_contents' => $cart_contents, 'shipping' => $shipping, 'total' => 0, 'i' => 0));
		}
		return View::make('carts/empty_cart', ['cart_contents' => []]);

	}

	public function add($product)
	{
		$product_id = Product::where('uri', $product)->pluck('id');
		if(!!Cart::where('user_session_id', Session::getId())->count()){
			$whereValues = ['user_session_id' => Session::getId(), 'product_id' => $product_id];
			if(!Cart::where($whereValues)->count()){
				$cart = new Cart;
				$cart->user_session_id = Session::getId();
				$cart->product_id = $product_id;
				$cart->quantity = 1;
				$cart->save();
				return Redirect::action('CartController@index');
			} else {
				$this->update($product_id);
				return Redirect::action('CartController@index');
			}
		} else {
			$this->create($product_id);
			return Redirect::action('CartController@index');
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($product_id)
	{
		$cart = new Cart;
		$cart->user_session_id = Session::getId();
		$cart->product_id = $product_id;
		$cart->quantity = 1;
		$cart->save();
		return $cart->id;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

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
	public function update($product_id = NULL, $quantity = 1)
	{
		if(Input::get('quantity')){
			foreach (Input::get('quantity') as $product_id => $item_quantity){
				if($item_quantity < 1){
					$this->destroy($product_id);
				} else {
					$whereValues = ['user_session_id' => Session::getId(), 'product_id' => $product_id];
					$cart = Cart::where($whereValues)->first();
					$cart->quantity = $item_quantity;
					$cart->save();
				}
			}
			return Redirect::action('CartController@index');
		} else {
			$whereValues = ['user_session_id' => Session::getId(), 'product_id' => $product_id];
			$cart = Cart::where($whereValues)->first();
			$cart->quantity += $quantity;
			$cart->save();

		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($product_id)
	{
		$whereValues = ['user_session_id' => Session::getId(), 'product_id' => $product_id];
		$cart_item = Cart::where($whereValues)->first();
		$cart_item->delete();
		return Redirect::action('CartController@index');
	}


}
