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
    $packages = array();
    if(array_key_exists('x_small', $package)) $packages[] = $package['x_small']/100;
    if(array_key_exists('small', $package)) $packages[] = $package['small']/24;
    if(array_key_exists('medium', $package)) $packages[] = $package['medium']/8;
    if(array_key_exists('large', $package)) $packages[] = $package['large']/2;

    return ceil(array_sum($packages));
}

function cartQuantity()
{
    $cart_contents = Cart::where('user_session_id', Session::getId())->get();
    $cart_quantity = 0;

    foreach($cart_contents as $item)
    {
        $cart_quantity += $item->quantity;
    }

    return $cart_quantity;
}

function cartTotal()
{
    $cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
    $cart_total = 0;

    foreach($cart_contents as $item)
    {
        $cart_total += $item->products['price'] * $item['quantity'];
    }

    return $cart_total;
}


function packageProducts($cart_contents)
{
    $package_size = [];
    foreach($cart_contents as $item)
    {
        $package_size[$item->products['size']] = $item->quantity;
    }

    $i = 1;
    $package = [];

    if(isset($package_size['large']))
    {
        $n = 0;
        if ($package_size['large'] > 1) {
            while ($n < floor($package_size['large'] / 2)) {
                $package[$i] = [1 => 2];
                $i++;
                $n++;
            }
        }
        if ($package_size['large'] % 2 > 0) {
            $package[$i] = [1 => 1];
            $modifier = 0.5;
        }
    }
    if(isset($package_size['medium']))
    {
        if(isset($modifier))
        {
            if($package_size['medium'] > $modifier * 8)
            {
                $package[$i] += [2 => $modifier * 8];
                $package_size['medium'] -= $package[$i][2];
                unset($modifier);
                $i++;
            }
            elseif($package_size['medium'] <= $modifier * 8)
            {
                $package[$i] += [2 => $package_size['medium']];
                $package_size['medium'] = 0;
                $modifier = $modifier + ($package_size['medium']/8);
            }
        }
        if($package_size['medium'] > 7)
        {
            $n = 0;
            while($n < floor($package_size['medium']/8))
            {
                $package[$i] = [2 => 8];
                $i++;
                $n++;
            }
        }
        if($package_size['medium']%8 > 0)
        {
            $package[$i] = [2 => $package_size['medium']%8];
            $modifier = ( 8 - $package[$i][2])/8;
        }
    }
    if(isset($package_size['small']))
    {
        $n = 0;
        if(isset($modifier))
        {
            if($package_size['small'] > $modifier * 24)
            {
                $package[$i] += [3 => floor($modifier * 24)];
                $package_size['small'] -= floor($modifier * 24);
                $i++;
            }
            elseif($package_size['small'] <= $modifier * 24)
            {
                $package[$i] += [3 => $package_size['small']];
                $package_size['small'] = 0;
            }
        }
        if($package_size['small'] > 23)
        {
            while($n < floor($package_size['small']/24))
            {
                $package[$i] = [3 => 24];
                $i++;
                $n++;
            }
        }
        if($package_size['small']%24 > 0)
        {
            $package[$i] = [3 => $package_size['small']%24];
        }
    }
    if(isset($package_size['x_small']))
    {
        if(isset($package_size['large']) || isset($package_size['medium']))
        {
            $package[$i] += [4 => $package_size['x_small']];
        }
        elseif(isset($package_size['x_small']))
        {
            $package[$i] = [4 => $package_size['x_small']];
        }


    }

    return $package;
}