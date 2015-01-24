@extends('layouts.checkout')
@section('content')
    <div class="content_wrapper">
        <div class="container_12">
            <div id="thanks">
                <h1>Thank you for making your purchase! Order ID: {{ $order_id }}</h1>
                <p>You should receive an email confirmation shortly.</p>
            </div>
        </div>
    </div>
@stop