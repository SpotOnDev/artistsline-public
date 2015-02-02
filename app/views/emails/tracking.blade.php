<?php $packages = unserialize($packages); ?>
<html>
    <body>
        <p>Your package containing the following has just shipped out:</p>
        <table border="0" cellspacing="3" cellpadding="3">
            <tr>
                <th>Item</th>
                <th>Quantity</th>
            </tr>
            @foreach($packages as $package)
                <tr><td>{{ $package->product['name'] }}</td>
                    <td align="center">{{ $package->quantity }}</td>
                </tr>
            @endforeach
        </table>
        <p>The tracking number for this package is: {{ $tracking }} .</p>
    </body>
</html>