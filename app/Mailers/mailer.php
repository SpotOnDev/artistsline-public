<?php namespace Mailers;

Use Mail;
abstract class Mailer {
    Public function sendTo($user, $subject, $view, $data)
    {
        Mail::queue($view, $data, function($message) use($user, $subject, $data)
        {
            $message->to($user->email)
					->bcc('adster2@gmail.com')
                    ->subject($subject);
        });
    }
}