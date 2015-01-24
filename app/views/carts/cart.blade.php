@extends('layouts.default')
@section('content')
    <div class="content_wrapper" id="cart-content">
        <div class="container_12">
            {{ Form::open(array('method' => 'patch', 'route' => 'cart.update')) }}
                <table id="cart-table">
                    <tr>
                        <th>Quantity</th>
                        <th></th><!--Column for little image of product-->
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th></th><!--Column for remove button-->
                    </tr>
                    @foreach($cart_contents as $item)
                    {{--$item[$i] = new Item();--}}
                    {{--$item[$i]->setName($cart_item->name)->setCurrency('USD')->setQuantity($cart_item->quantity)->setPrice($cart_item->price/100);--}}
                    {{--$ship->calcPackageSize($cart_item->size, $cart_item->quantity);--}}
                    <?php
                    $subtotal = $item->products['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr><td><input type="text" name="quantity[{{ $item['product_id'] }}]" value="{{ $item['quantity'] }}" id="qty"></td>
                    <td><a href="/products/<?= $item->products['uri']; ?>">{{ HTML::image('images/' . $item->products['uri'] . '_cart.jpg') }}</a></td>
                    <td>{{ $item->products['name'] }}</td>
                    <td><p>${{ number_format($item->products['price']/100, 2) }}</p></td>
                    <td><p>${{ number_format($subtotal/100, 2) }}</p></td>
                    <td><a href="{{ URL::Route('cart.delete', array('product_id' => $item['product_id'])) }}">{{ HTML::image('images/btn_trash.gif') }}</a></td>
                    </tr>
                    <?php $i++; ?>
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
                {{ HTML::link('/shipping', 'Checkout', array('id' => 'checkout-button', 'class' => 'cart-button')) }}
                {{ Form::submit('Update', array('class' => 'cart-button')) }}
            {{ Form::close() }}
        </div>
        <!--<a href="#"><img src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_checkout_pp_142x27.png"></a>-->
    </div>
@stop