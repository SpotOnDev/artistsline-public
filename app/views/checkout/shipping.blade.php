@extends('layouts.checkout')
@section('content')
    <div class="content_wrapper" id="checkout-content">
        <div class="container_12">
            {{ Form::open(array('id' => 'checkout-form-1', 'route' => 'shipping.store')) }}
                <ul>
                    <span class="error">{{ Session::get('easypost_error') }}</span>
                    <li>
                        <ul>
                            <li class="form-row">
                                <h3><span>*</span>First Name</h3>
                                @if(isset($first_name))
                                    {{ Form::text('first_name', $first_name) }}
                                @else
                                    {{ Form::text('first_name') }}
                                @endif
                                {{ $errors->first('first_name', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Last Name</h3>
                                @if(isset($last_name))
                                    {{ Form::text('last_name', $last_name) }}
                                @else
                                    {{ Form::text('last_name') }}
                                @endif
                                {{ $errors->first('last_name', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Email</h3>
                                @if(isset($email))
                                    {{ Form::text('email', $email) }}
                                @else
                                    {{ Form::text('email') }}
                                @endif
                                {{ $errors->first('email', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Address</h3>
                                @if(isset($address1))
                                    {{ Form::text('address', $address1) }} <br>
                                    {{ Form::text('address2', $address2) }}
                                @else
                                    {{ Form::text('address') }} <br>
                                    {{ Form::text('address2') }}
                                @endif
                                {{ $errors->first('address', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>City</h3>
                                @if(isset($city))
                                    {{ Form::text('city', $city) }}
                                @else
                                    {{ Form::text('city') }}
                                @endif
                                {{ $errors->first('city', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>State</h3>
                                @if(isset($state))
                                    {{ Form::select('state', $states, $state) }}
                                @else
                                    {{ Form::select('state', $states) }}
                                @endif
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Zip Code</h3>
                                @if(isset($zip))
                                    {{ Form::text('zipcode', $zip) }}
                                @else
                                    {{ Form::text('zipcode') }}
                                @endif
                                {{ $errors->first('zipcode', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Phone</h3>
                                @if(isset($phone))
                                    {{ Form::text('phone', $phone) }}
                                @else
                                    {{ Form::text('phone') }}
                                @endif
                                {{ $errors->first('phone', '<span class=error>:message</span>') }}
                            </li>
                        </ul>
                    </li>
                    <li>
                        @if(!Session::has('billing_form'))
                            {{ Form::checkbox('use', 'Y') }}
                        @endif
                        {{ Form::label('use', 'Use Same Address for Billing') }}
                    </li>
                </ul>
                {{ Form::submit('Continue', array('id' => 'checkout-submit')) }}
            {{ Form::close() }}
        </div>
    </div>
@stop