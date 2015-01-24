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
            <span><?php if (isset($e_message)) echo $e_message; ?></span>
            {{ Form::open(array('route' => 'pages.contact', 'id' => 'contact-form')) }}
                <div>
                    <h3>Name</h3>
                    {{ Form::text('name') }}
                </div>
                <div>
                    <h3>E-mail</h3>
                    {{ Form::text('email') }}
                </div>
                <div>
                    <h3>Message</h3>
                    {{ Form::textarea('message') }}
                </div>
                {{ Form::submit('Submit', array('id' => 'contact-submit')) }}
            {{ Form::close() }}
        </div>
    </div>
@stop