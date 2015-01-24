<?php namespace Shipping;

interface ShippingInterface {
    public function address(array $data);

    public function create(array $data, $cart_contents);
}