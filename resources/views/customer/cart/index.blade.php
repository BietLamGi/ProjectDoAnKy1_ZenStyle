@extends('layouts.app.app')

@section('title', 'Shopping Cart')

@section('content')

<div class="cart-page">

    {{-- HEADER --}}
    <div class="cart-container">

        <div class="cart-header">

            <a href="{{ route('services') }}" class="cart-back-btn">
                <i class="icon-arrow-left"></i>
            </a>

            <div class="cart-header-text">
                <h2>Shopping Cart</h2>
                <p>Review your selected products</p>
            </div>

        </div>


        @if(empty($cart))

        {{-- EMPTY CART --}}
        <div class="cart-empty">

            <div class="cart-empty-icon">
                <i class="icon-shopping-cart"></i>
            </div>

            <h4>Your cart is currently empty.</h4>

            <p>
                Browse our products and add something to your cart.
            </p>

            <a href="{{ route('services') }}" class="cart-continue-btn">

                Continue Shopping

            </a>

        </div>


        @else

        {{-- CART CONTENT --}}
        <div class="cart-layout">


            {{-- LEFT --}}
            <div class="cart-items">

                @foreach($cart as $item)

                <div class="cart-item">

                    {{-- IMAGE --}}
                    <div class="cart-product-image">

                        @if(!empty($item['image']))

                        <img src="{{ asset('frontend/images/' . $item['image']) }}" alt="{{ $item['name'] }}">

                        @else

                        <div class="cart-no-image">
                            <i class="icon-shopping-bag"></i>
                        </div>

                        @endif

                    </div>


                    {{-- PRODUCT --}}
                    <div class="cart-product-info">

                        <h5>
                            {{ $item['name'] }}
                        </h5>

                    </div>


                    {{-- QUANTITY --}}
                    <div class="cart-quantity">

                        <span class="quantity-label">
                            Quantity
                        </span>

                        <div class="quantity-control">

                            {{-- MINUS --}}
                            <form method="POST" action="{{ route('cart.update') }}">

                                @csrf

                                <input type="hidden" name="id" value="{{ $item['id'] }}">

                                <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">

                                <button type="submit" class="quantity-btn"
                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                    −
                                </button>

                            </form>


                            {{-- NUMBER --}}
                            <span class="quantity-number">
                                {{ $item['quantity'] }}
                            </span>


                            {{-- PLUS --}}
                            <form method="POST" action="{{ route('cart.update') }}">

                                @csrf

                                <input type="hidden" name="id" value="{{ $item['id'] }}">

                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">

                                <button type="submit" class="quantity-btn">
                                    +
                                </button>

                            </form>

                        </div>

                    </div>

                    {{-- UNIT PRICE --}}
                    <div class="cart-product-price">

                        <span>
                            Price
                        </span>

                        <strong>
                            {{ number_format($item['price']) }} VND
                        </strong>

                    </div>


                    {{-- REMOVE --}}
                    <div class="cart-remove">

                        <form method="POST" action="{{ route('cart.remove') }}">

                            @csrf

                            <input type="hidden" name="id" value="{{ $item['id'] }}">

                            <button type="submit" class="cart-remove-btn" title="Remove">
                                x
                            </button>

                        </form>

                    </div>

                </div>

                @endforeach

            </div>


            {{-- RIGHT SUMMARY --}}
            <div class="cart-summary">

                <h4>
                    Order Summary
                </h4>

                <div class="cart-summary-line"></div>


                @php
                $total = 0;
                $cartCount = 0;
                @endphp


                {{-- PRODUCTS IN SUMMARY --}}
                @foreach($cart as $item)

                @php
                $subtotal = $item['price'] * $item['quantity'];

                $total += $subtotal;
                $cartCount += $item['quantity'];
                @endphp


                <div class="cart-summary-product">

                    <div>
                        <span class="cart-summary-name">
                            {{ $item['name'] }}
                        </span>

                        <small>
                            × {{ $item['quantity'] }}
                        </small>
                    </div>

                    <strong>
                        {{ number_format($subtotal) }} VND
                    </strong>

                </div>

                @endforeach


                {{-- ITEM COUNT --}}
                <div class="cart-summary-row">

                    <span>
                        Items
                    </span>

                    <strong>
                        {{ $cartCount }}
                    </strong>

                </div>


                {{-- TOTAL --}}
                <div class="cart-total">

                    <span>
                        Total
                    </span>

                    <strong>
                        {{ number_format($total) }} VND
                    </strong>

                </div>


                {{-- CHECKOUT --}}
                <form method="GET" action="{{ route('checkout') }}">

                    <button type="submit" class="cart-checkout-btn">

                        Proceed to Checkout

                    </button>

                </form>


                {{-- CONTINUE SHOPPING --}}
                <a href="{{ route('services') }}" class="cart-continue-shopping">

                    Continue Shopping

                </a>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection