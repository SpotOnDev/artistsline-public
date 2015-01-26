@extends('layouts.default')
@section('content')
    <div class="content_wrapper" id="contact-content">
        <div class="container_12">
            <div id="contact-info">
                <h3><span>Con</span>tact Info</h3>
                <ul>
                    <li><i class="fa fa-map-marker"></i><p>Miami, FL 33179</p></li>
                    <li><i class="fa fa-phone"></i><p>(800)601-1552</p></li>
                    <li><i class="fa fa-envelope"></i><p>contact@artistsline.com</p></li>
                </ul>
                <p>
            </div>
            @if (Session::has('e_message'))
                <div class="error">
                    {{ Session::get('e_message') }}
                </div>
            @endif
            {{ Form::open(array('route' => 'pages.contact', 'id' => 'contact-form')) }}
                <div>
                    <h3>Name</h3>
                    {{ Form::text('name') }}<br>
					{{ $errors->first('name', '<span class=error>:message</span>') }}
                </div>
                <div>
                    <h3>E-mail</h3>
                    {{ Form::text('email') }}<br>
					{{ $errors->first('email', '<span class=error>:message</span>') }}
                </div>
                <div>
                    <h3>Message</h3>
                    {{ Form::textarea('message') }}
                    {{ $errors->first('message', '<span class=error>:message</span>') }}
                </div>
                {{ Form::submit('Submit', array('id' => 'contact-submit')) }}
            {{ Form::close() }}
        </div>
    </div>
@stop