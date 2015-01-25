<?php
class Shopper extends Eloquent
{
    public static $rules = [
        'first_name' => 'required|alpha|max:20',
        'last_name' => 'required|alpha|max:40',
        'email' => 'required|email',
        'address' => 'required',
        'city' => 'required|alpha_spaces',
        'zipcode' => 'required|numeric',
        'phone' => 'required|phone:US|max:20'
    ];
}