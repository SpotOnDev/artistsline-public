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
            $shipment = Shipment::create(array(
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
            $shipment->buy($shipment->lowest_rate(array('USPS'), array('First', 'Priority')));
            $tracking_code = json_decode($shipment, true);
            $tracking[$i] = $tracking_code['tracking_code'];
            $i++;
        }
        return $tracking;
    }

}