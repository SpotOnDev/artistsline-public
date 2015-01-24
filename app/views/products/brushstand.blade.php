@extends('layouts.default')
@section('content')
    <div class="content_wrapper" id="stand-wrap">
        <div id="img-viewer">{{ HTML::image('images/wire_stand.jpg', NULL,  array('id' => 'img')) }}</div>
        <div id="add-photos">
            <a href="#">{{ HTML::image('images/wire_stand_thumb.jpg') }}</a>
            <a href="#">{{ HTML::image('images/multi_stand_thumb.jpg') }}</a>
            <a href="#">{{ HTML::image('images/wirestand_top_thumb.jpg') }}</a>
        </div>
        <div id="product-info">
            <h2>Brush Line Stand</h2>
            <p>
                An innovative and revolutionary product for which to store your brushes in the bristles-down
                position. This allows them to be displayed during the painting session at eye-level for quick
                selection. 120 brushes can be stored or displayed on this multi-hanger stand with 8 wires attached.
                You can secure this device with ease to the tabletop (up to 6" thickness). Each package contains
                1 table clamp, an extendable three-part pole, a supporting plate, 1 stainless steel wire and 10
                brush tails.
            </p>
            <h3>$39.85</h3>
            {{ Form::open(array('method' => 'put', 'route' => array('cart.add', $product))) }}
            {{ Form::submit('Add to Cart', array('id' => 'add-to-cart')) }}
            {{ Form::close() }}
        </div>

    </div>
    {{ HTML::script('js/gallery.js', array("type" => "text/javascript")) }}
@stop