@extends('layouts.default')
@section('content')
    <div class="content_wrapper" id="wire-wrap">
        <div id="img-viewer">{{ HTML::image('images/wire.jpg', NULL, array('id' => 'img')) }}</div>
        <div id="product-info">
            <h2>Stainless-Steel Lines</h2>
            <p>
                For the Table-Stand version, which can hold up to eight. Each package contains 2 durable, stainless steel lines.
            </p>
            <h3>$12.95</h3>
            {{ Form::open(array('method' => 'put', 'route' => array('cart.add', $product))) }}
            {{ Form::submit('Add to Cart', array('id' => 'add-to-cart')) }}
            {{ Form::close() }}
        </div>

    </div>
    {{ HTML::script('js/gallery.js') }}
@stop