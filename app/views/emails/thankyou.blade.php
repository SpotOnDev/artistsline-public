<?php $cart = unserialize($cart); ?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <p>Thank you for your order. Your order number is {{ $order_id  }} and Tracking number(s): {{ $tracking_numbers }}. All orders are processed on the next business day. You will be contacted in case of any delays.</p>
        <table border="0" cellspacing="3" cellpadding="3">
            <tr>
                <th align="center">Item</th>
                <th align="center">Quantity</th>
                <th align="right">Price</th>
                <th align="right">Subtotal</th>
            </tr>
            @foreach($cart as $item)
                <tr><td>{{ $item->products['name'] }}</td>
                    <td align="center">{{ $item['quantity'] }}</td>
                    <td align="right">${{ number_format($item->products['price']/100, 2) }}</td>
                    <td align="right">${{ number_format(($item->products['price']*$item['quantity'])/100, 2) }}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="2"> </td><th align="right">Shipping</th>
                    <td align="right">${{ number_format(SHIP_RATE * $package_amount/100, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2"> </td><th align="right">Total</th>
                    <td align="right">${{ number_format($total+SHIP_RATE/100, 2) }}</td>
                </tr>
        </table>
    </body>
</html>