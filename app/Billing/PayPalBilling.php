<?php namespace Billing;
use Config;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use URL;
class PayPalBilling implements BillingInterface {
    private $_api_context;

    public function __construct()
    {
        $paypal_config = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_config['client_id'], $paypal_config['secret']));
        $this->_api_context->setConfig($paypal_config['settings']);
    }
    public function charge(array $data)
    {
        $payment = Payment::get($data['token'], $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($data['payer_id']);
        $result = $payment->execute($execution, $this->_api_context);

        return $result;
    }

    public function generatePayer($cart_contents)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        $shipping = calculateShipping($cart_contents) * SHIP_RATE;
        if(cartTotal($cart_contents) > 3000) $shipping = 0;

        $i = 1;
        $item = [];
        $items = [];
        foreach($cart_contents as $cart_item)
        {
            $item[$i] = new Item();
            $item[$i]->setName($cart_item->products['name'])
                ->setCurrency('USD')
                ->setQuantity($cart_item['quantity'])
                ->setPrice($cart_item->products['price']/100);
            $items[] = $item[$i];
            $i++;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping($shipping/100)
            ->setSubtotal(cartTotal()/100);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal((cartTotal() + $shipping)/100)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('paypal_review.index'))
            ->setCancelUrl(URL::route('cart.index'));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        $payment->create($this->_api_context);

        return $payment;
    }

    public function retrieveCustomer(array $data)
    {
        $payment = Payment::get($data['token'], $this->_api_context);
        $customer = $payment->payer->payer_info;

        return $customer;
    }
}