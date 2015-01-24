<?php namespace Mailers;

class UserMailer extends Mailer {
    public function confirm($user, $data)
    {
        $view = 'emails.thankyou';
        $subject = 'Order Confirmation';

        return $this->sendTo($user, $subject, $view, $data);
    }
}