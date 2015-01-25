<?php namespace Mailers;

use Config;
class UserMailer extends Mailer {
    public function confirm($user, $data)
    {
        $view = 'emails.thankyou';
        $subject = 'Order Confirmation';
        $bcc = Config::get('bcc_emails.bcc');

        return $this->sendTo($user, $subject, $view, $data, $bcc);
    }
}