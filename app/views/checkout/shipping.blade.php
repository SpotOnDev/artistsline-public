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
                                {{ Form::text('first_name') }}
                                {{ $errors->first('first_name', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Last Name</h3>
                                {{ Form::text('last_name') }}
                                {{ $errors->first('last_name', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Email</h3>
                                {{ Form::text('email') }}
                                {{ $errors->first('email', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Address</h3>
                                {{ Form::text('address') }} <br>
                                {{ Form::text('address2') }}
                                {{ $errors->first('address', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>City</h3>
                                {{ Form::text('city') }}
                                {{ $errors->first('city', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>State</h3>
                                {{ Form::select('state', $states) }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Zip Code</h3>
                                {{ Form::text('zipcode') }}
                                {{ $errors->first('zipcode', '<span class=error>:message</span>') }}
                            </li>
                            <li class="form-row">
                                <h3><span>*</span>Phone</h3>
                                {{ Form::text('phone') }}
                                {{ $errors->first('phone', '<span class=error>:message</span>') }}
                            </li>
                        </ul>
                    </li>
                    <li>
                        {{ Form::checkbox('use', 'Y') }}
                        {{ Form::label('use', 'Use Same Address for Billing') }}
                    </li>
                </ul>
                {{ Form::submit('Continue', array('id' => 'checkout-submit')) }}
            {{ Form::close() }}
        </div>
    </div>
@stop