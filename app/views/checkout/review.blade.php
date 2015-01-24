@extends('layouts.checkout')
@section('content')
    <div id="loading"></div>
    <div class="content_wrapper" id="cart-content">
        <script src="js/loading.js"></script>
        <div class="container_12">
            @if (Session::has('flash_message'))
                <div class="error">
                    {{ Session::get('flash_message') }}
                </div>
            @endif
            <div id="address-billing">
                <div id="shipping">
                    <h1>Shipping Info</h1>
                    <ul>
                        <li>{{ $customer->first_name }} {{ $customer->last_name }}</li>
                        <li>{{ $customer->address1 }} {{$customer->address2}}</li>
                        <li>{{ $customer->city }} {{ $customer->state }} {{ $customer->zip }}</li>
                        <li>{{ $customer->email }}</li>
                        <li>{{ $customer->phone }}</li>
                        <li>{{ HTML::link('shipping', 'Edit') }}</li>
                    </ul>
                </div>
                <div id="billing">
                    <h1>Billing Info</h1>
                    <ul>
                    <li>{{ $billing->name }}</li>
					<li>{{ $billing->address_line1 }} {{ $billing->address_line2 }}</li>
					<li>{{ $billing->address_city }} {{ $billing->address_state }} {{ $billing->address_zip }}</li>
					<li>Card: {{ str_repeat("*",12) }}{{ $billing->last4 }}</li>
					<li>{{ HTML::link('billing', 'Edit') }}</li>
                    </ul>
                </div>
            </div>
            <table id="review-cart-table">
                <tr>
                    <th>Quantity</th>
                    <th></th><!--Column for little image of product-->
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                @foreach($cart_contents as $item)
                    <?php
                    $subtotal = $item->products['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr><td>{{ $item['quantity'] }}</td>
                        <td><a href="/products/<?= $item->products['uri']; ?>">{{ HTML::image('images/' . $item->products['uri'] . '_cart.jpg') }}</a></td>
                        <td>{{ $item->products['name'] }}</td>
                        <td><p>${{ number_format($item->products['price']/100, 2) }}</p></td>
                        <td><p>${{ number_format($subtotal/100, 2) }}</p></td>
		            </tr>
                @endforeach
            </table>
		<div id="total">
			<p>
                Shipping:<span>${{ number_format(($shipping)/100, 2) }}</span>
			</p>
			<p>
                Cart Total:<span>${{ number_format($total/100, 2) }}</span>
			</p>
		</div>
            {{ Form::open(array('id' => 'review_form')) }}
                {{ HTML::link('cart', 'Edit Cart', ['class' => 'cart-button']) }}
                <input type="submit" value="Place Order" id="review-submit" class="cart-button">
                {{ Form::checkbox('agree_terms', 'Y', null, ['id' => 'agree_terms']) }}
                <label for="agree_terms">Confirm Order</label>
            {{ Form::close() }}
        </div>
    </div>
@stop