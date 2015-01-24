<!doctype html>
<html>
<head>
    @include('includes.head')
</head>
<body>
    @include('includes.header')
    @yield('content')
    <footer>
        @include('includes.footer')
    </footer>
</body>
</html>