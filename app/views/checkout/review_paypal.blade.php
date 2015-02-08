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
                        <li>{{ $shopper->first_name }} {{ $shopper->last_name }}</li>
                        <li>{{ $shopper->shipping_address->line1 }} {{ $shopper->shipping_address->line2 }}</li>
                        <li>{{ $shopper->shipping_address->city }} {{ $shopper->shipping_address->state }} {{ $shopper->shipping_address->postal_code }}</li>
                        <li>{{ $shopper->email }}</li>
                        <li>{{ HTML::linkaction('CartController@postPayment', 'Edit') }}</li>
                    </ul>
                </div>
                <div id="billing">
                    <h1>Billing Info</h1>
                    <ul>
                        <li>PayPal</li>
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
            {{ Form::open(['route' => 'paypal_review.store','id' => 'review_form']) }}
            {{ HTML::link('cart', 'Edit Cart', ['class' => 'cart-button']) }}
            <input type="submit" value="Place Order" id="review-submit" class="cart-button">
            {{ Form::checkbox('agree_terms', 'Y', null, ['id' => 'agree_terms']) }}
            <label for="agree_terms">Confirm Order</label>
            {{ Form::close() }}
        </div>
    </div>
@stop