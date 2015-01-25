<?php namespace Mailers;

Use Mail;
abstract class Mailer {
    Public function sendTo($user, $subject, $view, $data, $bcc)
    {
        Mail::queue($view, $data, function($message) use ($user, $subject, $bcc, $data)
        {
            $message->to($user->email)
					->bcc($bcc)
                    ->subject($subject);
        });
    }
}