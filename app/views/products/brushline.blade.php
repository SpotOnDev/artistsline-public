@extends('layouts.default')
@section('content')
    <div class="content_wrapper" id="clamp-wrap">
        <div id="img-viewer">{{ HTML::image('images/wire_clamp.jpg', NULL,  array('id' => 'img')) }}</div>
        <div id="add-photos">
            <a href="#">{{ HTML::image('images/wire_clamp_thumb.jpg') }}</a>
            <a href="#">{{ HTML::image('images/clamp_back_thumb.jpg') }}</a>
        </div>
        <div id="product-info">
            <h2>Brush Line</h2>
            <p>
                The  Brush Line is a great new way to support your brushes during a painting session and works for
                storing as well.  You can attach the Brush Line to an easel or canvas frame (large or small) without
                disturbing the front of the canvas.  Product includes: 1 clamp, 2 screws (for adjustments), 1 wire
                that holds up to 15 brushes, and 6 brush tails.  Additional brush tails can be purchased separately.
            </p>
            <h3>$24.85</h3>
            {{ Form::open(array('method' => 'put', 'route' => array('cart.add', $product))) }}
                {{ Form::submit('Add to Cart', array('id' => 'add-to-cart')) }}
            {{ Form::close() }}
        </div>

    </div>
    {{ HTML::script('js/gallery.js', array("type" => "text/javascript")) }}
@stop