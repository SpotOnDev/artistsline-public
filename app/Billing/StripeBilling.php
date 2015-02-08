<?php namespace Billing;
use Stripe;
use Stripe_Charge;
use Config;
use Stripe_Token;
use Stripe_CardError;
use Exception;
use Stripe_InvalidRequestError;

class StripeBilling implements BillingInterface {
    public function __construct()
    {
        Stripe::setApiKey(Config::get('stripe.secret_key'));
    }
    public function charge(array $data)
    {
        $charge = Stripe_Charge::create([
            'amount' => $data['total'],
            'currency' => 'usd',
            'description' => 'Order ID: ' . $data['order_id'],
            'card' => $data['token']
        ]);

        return $charge;
    }
    public function retrieveCustomer(array $data)
    {
        return Stripe_Token::retrieve($data['token']);
    }
}