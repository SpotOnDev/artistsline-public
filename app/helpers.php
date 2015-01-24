<?php
define('SHIP_RATE', 590);
define('FREE_SHIP', 3000);

function calculateShipping($cart_contents = [])
{
    $package = array();
    foreach($cart_contents as $item)
    {
        $package[$item->products['size']] = $item->quantity;
    }

    if(isset($package['medium']))
    {
        if(isset($package['small']))
        {
            $package['small'] += $package['medium'] * 2;
        } else
        {
            $package['small'] = $package['medium'] * 2;
        }
    }
    $package_amount = 1;
    if (array_key_exists('x_small', $package) && !array_key_exists('small', $package) && !array_key_exists('large', $package)) {
        $package_amount = 1;
    } elseif (array_key_exists('small', $package) && array_key_exists('large', $package)) {
        $package_amount = 1;
        if (($package['small'] > 4) || ($package['large'] > 1)) {
            if ($package['small'] > ($package['large'] * 4)) {
                $package_amount = ceil(($package['small'] - (4 * $package['large'])) / 12) + $package['large'];
            } else {
                $package_amount = ceil(($package['small'] / 4) + ($package['large'] - ($package['large'] / 4)) / 2);
            }
        }
    } elseif (array_key_exists('small', $package)) {
        $package_amount = ceil($package['small'] / 12);
    } elseif (array_key_exists('large', $package)) {
        $package_amount = ceil($package['large'] / 2);
    }
    return $package_amount;
}

function cartQuantity($cart_contents = [])
{
    $cart_quantity = 0;

    foreach($cart_contents as $item)
    {
        $cart_quantity =+ $item->quantity;
    }

    return $cart_quantity;
}

function cartTotal($cart_contents = [])
{
    $cart_total = 0;

    foreach($cart_contents as $item)
    {
        $cart_total =+ $item->products['price'] * $item->quantity;
    }

    return $cart_total;
}