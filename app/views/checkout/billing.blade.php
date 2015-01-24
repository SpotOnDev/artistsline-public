@extends('layouts.checkout')
@section('content')
    <div class="content_wrapper" id="checkout-content">
        <div class="container_12">
            <span class="error" id="error_span"></span>
            {{ Form::open(['id' => 'checkout-form-2']) }}
                <div class="error"></div>
                <ul>
                    <li>
                        <ul id="left-col">
                            <li class="form-row">
                                <h3><span>*</span>Name on card</h3>
                                {{ Form::text('name', Input::old('first_name') . ' ' . Input::old('last_name'), ['data-stripe' => 'name']) }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Address</h3>
                                {{ Form::text('address', null, ['data-stripe' => 'address_line1']) }}
                                {{ Form::text('address2', null, ['data-stripe' => 'address_line2']) }}
                            <li class="form-row">
                                <h3><span>*</span>City</h3>
                                {{ Form::text('city', null, ['data-stripe' => 'address_city']) }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>State</h3>
                                {{ Form::select('state', $states, null, ['data-stripe' => 'address_state']) }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Zip Code</h3>
                                {{ Form::text('zipcode', null, ['data-stripe' => 'address_zip']) }}
                            </li>
                        </ul>
                        <ul id="right-col">
                            <li class="form-row">
                                <h3><span>*</span>Credit / Debit Card Number</h3>
                                <input type="text" autocomplete="off" data-stripe="number">
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Expiration Date</h3>
                                {{ Form::selectMonth(null, null, ['data-stripe' => 'exp-month']) }}
                                {{ Form::selectYear(null, date('Y'), date('Y') + 10, null, ['data-stripe' => 'exp-year']) }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Security Code</h3>
                                <input type="text" autocomplete="off" data-stripe="cvc">
                            </li>
                        </ul>
                    </li>
                    <li>
                    </li>
                </ul>
                {{ Form::submit('Continue', ['id' => 'checkout-submit']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop