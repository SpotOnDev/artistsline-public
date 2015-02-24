<?php

class WebhookController extends \BaseController {

    public function packageShipped()
    {
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);
        $packages = Package::where('trk_id', $event_json->result->id)->get();
        if($packages->first()->status == 'pre_transit' || $packages->first()->status == '') $email_sent = false;
        foreach($packages as $package)
        {
            $package->status = $event_json->result->status;
            $package->save();
        }
        $tracking_number = $event_json->result->tracking_code;

        $customer_id = Order::find($packages->first()->order_id)->pluck('customer_id');
		$customer = Customer::find($customer_id)->get();
        $email = $customer->email;
		$customer_name = $customer->first_name . ' ' . $customer->last_name;

        if(!$email_sent && $event_json->result->status == 'in_transit')
        {
            $packages = serialize($packages);
            Mail::queue(['html' => 'emails.tracking'], ['packages' => $packages, 'tracking' => $tracking_number], function ($message) use ($email, $customer_name) {
                $message->to($email, $customer_name)->subject('Package Has Shipped');
            });
        }

        http_response_code(200);
    }
}