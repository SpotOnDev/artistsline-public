<header class="container_12">
    <h1 id="logo">
        <a href="/"></a>
    </h1>
    <a href="/cart" id="header-cart">Cart({{ cartQuantity() }}) | ${{ number_format(cartTotal()/100, 2) }} <i class="fa fa-shopping-cart"></i></a>
</header>
<navigation>
    <div class="container_12">
        <ul>
            <li>{{ HTML::link('/', 'home') }}</li>
            <li>{{ HTML::link('/products', 'products') }}</li>
            <li>{{ HTML::link('/about', 'about') }}</li>
            <li>{{ HTML::link('/contact', 'contact') }}</li>
        </ul>
    </div>
</navigation>
