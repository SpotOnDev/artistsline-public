<!doctype html>
<html>
    <head>
        @include('includes.checkout_head')
    </head>
    <body>
        @include('includes.checkout_header')
        @yield('content')
        <footer>
            @include('includes.footer')
        </footer>
    </body>

    <script src="https://js.stripe.com/v2/"></script>
    <script src="/js/billing.js"></script>
</html>