@extends('layouts.default')
@section('content')
    <div class="content_wrapper" id="brush-wrap">
        <div id="img-viewer">{{ HTML::image('images/brush_tails.jpg', NULL, array('id' => 'img')) }}</div>
        <div id="add-photos">
            <a href="#">{{ HTML::image('images/brush_tails_thumb.jpg') }}</a>
            <a href="#">{{ HTML::image('images/brush_tails_2_thumb.jpg') }}</a>
        </div>
        <div id="product-info">
            <h2>Brush Tail</h2>
            <p>
                The elasticity of the patented Brush Tails allow them to easily fit nearly all brush sizes
                as well as any other similar items that you may want to use with the Brush Line. Includes 10 Brush Tails.
            </p>
            <h3>$7.96</h3>
            {{ Form::open(array('method' => 'put', 'route' => array('cart.add', $product))) }}
            {{ Form::submit('Add to Cart', array('id' => 'add-to-cart')) }}
            {{ Form::close() }}
        </div>

    </div>
    {{ HTML::script('js/gallery.js') }}
@stop