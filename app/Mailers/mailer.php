<?php namespace Mailers;

Use Mail;
abstract class Mailer {
    public function sendTo($user, $subject, $view, $data, $bcc)
    {
        Mail::queue($view, $data, function($message) use ($user, $subject, $bcc, $data)
        {
            $message->to($user->email)
					->bcc($bcc)
                    ->subject($subject);
        });
    }
	
	public function contact($from, $to, $subject, $view, $data)
	{
		Mail::queue($view, $data, function($message) use ($from, $to, $subject, $data)
	   {
			$message->from($from);
		   	$message->to($to)
					->subject($subject);
	   });
	}
}