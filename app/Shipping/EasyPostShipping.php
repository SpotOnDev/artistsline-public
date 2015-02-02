<?php namespace Shipping;

use EasyPost\EasyPost;
use EasyPost\Address;
use EasyPost\Shipment;
use Config;

class EasyPostShipping implements ShippingInterface {
    public function __construct()
    {
        EasyPost::setApiKey(Config::get('easypost.secret_key'));
    }

    public function address(array $data)
    {
        return Address::create_and_verify([
            'street1' => $data['address'],
            'street2' => $data['address2'],
            'zip' => $data['zipcode'],
            'city' => $data['city'],
            'state' => $data['state'],
        ]);

    }

    public function create(array $data, $cart_contents)
    {
        $package_amount = calculateShipping($cart_contents);

        $i = 0;
        while ($i < $package_amount) {
            $package = Shipment::create(array(
                'to_address' => array(
                    'name' => $data['name'],
                    'street1' => $data['address'],
                    'street2' => $data['address2'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip' => $data['zip']
                ),
                'from_address' => array(
                    'company' => 'Artists Line',
                    'street1' => '20514 NE 9th CT',
                    'city' => 'Miami',
                    'state' => 'FL',
                    'zip' => '33179'
                ),
                'parcel' => array(
                    'predefined_package' => 'FlatRateLegalEnvelope',
                    'weight' => 32
                )
            ));
            $package->buy($package->lowest_rate(array('USPS'), array('First', 'Priority')));
            $package_object = json_decode($package);
            $shipment[$i] = $package_object;
            $i++;
        }
        return $shipment;
    }

}