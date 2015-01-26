<?php
use Mailers\UserMailer as Mailer;

class ContactController extends \BaseController {

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function index()
    {
        $cart_contents = Cart::with('products')->where('user_session_id', Session::getId())->get();
        return View::make('pages/contact', array('cart_contents' => $cart_contents));
    }

    public function store()
    {
        $name = Input::get('name');
        $body = Input::get('message');
        $validator = Validator::make(
            array(
                'name' => $name,
                'email' => Input::get('email'),
                'message' => $body
            ),
            array(
                'name' => 'required|alpha_spaces',
                'email' => 'required|email',
                'message' => 'required'
            )
        );

        if($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator->messages());
        }

        $data = [
            'name' => $name,
            'body' => $body
        ];
        $this->mailer->contactUs(Input::get('email'), $data);
        return Redirect::back()->with('e_message', 'Your email has been sent!');
    }
}