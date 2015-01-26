<?php namespace Mailers;

use Config;
class UserMailer extends Mailer {
    public function confirm($user, $data)
    {
        $view = 'emails.thankyou';
        $subject = 'Order Confirmation';
        $bcc = Config::get('emails.bcc');

        return $this->sendTo($user, $subject, $view, $data, $bcc);
    }
	
	public function contactUs($from, $data)
	{
		$view = 'emails.contact';
		$to = Config::get('emails.contact');
		$subject = 'Inquery From Artists Line';
		
		return $this->contact($from, $to, $subject, $view, $data);
	}
}