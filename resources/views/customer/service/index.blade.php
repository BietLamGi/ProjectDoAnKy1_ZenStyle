@extends('layouts.app.app')

@section('title', 'Our Services')

@section('content')

<section class="service-section">

    <div class="container">

        {{-- ===================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================== --}}

        <div class="text-center mb-5">

            <h1>Our Services</h1>

            <p>
                Discover professional Hair Salon, Skin Care and Massage services.
            </p>

        </div>


        {{-- ================================================== --}}
        {{-- HAIR SALON --}}
        {{-- ================================================== --}}

        <div class="text-center mb-5">

            <h2>Hair Salon</h2>

            <p>
                Professional Hair Care Services
            </p>

        </div>


        {{-- ===================== --}}
        {{-- HAIR SERVICES --}}
        {{-- ===================== --}}

        <div class="row">

            @forelse($hairServices as $service)

            <div class="col-lg-4 col-md-6 mb-4 d-flex">

                <div class="service-card w-100">

                    <img src="{{ asset('frontend/images/'.$service->Image) }}" class="service-image"
                        alt="{{ $service->ServiceName }}">

                    <div class="service-body text-center">

                        <h4 class="service-title">
                            {{ $service->ServiceName }}
                        </h4>

                        <p class="service-desc">
                            {{ $service->Description }}
                        </p>

                        <div class="service-price">
                            @if($service->activePromotion)
                            <span class="text-muted text-decoration-line-through">
                                {{ number_format($service->Price) }} VND
                            </span>
                            <strong class="text-danger">
                                {{ number_format($service->discounted_price) }} VND
                            </strong>
                            @if($service->activePromotion->DiscountType === 'Percent')
                            <span class="badge bg-danger">
                                -{{ $service->activePromotion->DiscountValue }}%
                            </span>
                            @else
                            <span class="badge bg-danger">
                                -{{ number_format($service->activePromotion->DiscountValue) }} VND
                            </span>
                            @endif
                            @else
                            {{ number_format($service->Price) }} VND
                            @endif
                        </div>

                        <div class="service-time">
                            {{ $service->DurationMinutes }} Minutes
                        </div>

                        {{-- BOOK SERVICE --}}
                        <a href="{{ route('booking', ['service' => $service->ServiceID]) }}"
                            class="btn btn-primary book-btn">
                            Book Now
                        </a>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center">
                No Hair Services.
            </div>

            @endforelse

        </div>


        <hr class="section-divider">


        {{-- ===================== --}}
        {{-- HAIR PRODUCTS --}}
        {{-- ===================== --}}

        <div class="text-center mb-5">

            <h3>Recommended Hair Products</h3>

            <p>
                Professional products used in our salon.
            </p>

        </div>


        <div class="row">

            @forelse($hairProducts as $product)

            <div class="col-lg-4 col-md-6 mb-4 d-flex">

                <div class="product-card w-100" id="product-{{ $product->ServiceID }}">

                    <img src="{{ asset('frontend/images/'.$product->Image) }}" class="product-image"
                        alt="{{ $product->ServiceName }}">

                    <div class="service-body text-center">

                        <h5 class="product-title">
                            {{ $product->ServiceName }}
                        </h5>

                        <p class="service-desc">
                            {{ $product->Description }}
                        </p>

                        <div class="product-price">
                            @if($product->activePromotion)
                            <span class="text-muted text-decoration-line-through">
                                {{ number_format($product->Price) }} VND
                            </span>
                            <strong class="text-danger">
                                {{ number_format($product->discounted_price) }} VND
                            </strong>
                            @if($product->activePromotion->DiscountType === 'Percent')
                            <span class="badge bg-danger">
                                -{{ $product->activePromotion->DiscountValue }}%
                            </span>
                            @else
                            <span class="badge bg-danger">
                                -{{ number_format($product->activePromotion->DiscountValue) }} VND
                            </span>
                            @endif
                            @else
                            {{ number_format($product->Price) }} VND
                            @endif
                        </div>


                        {{-- PRODUCT ACTIONS --}}
                        <div class="product-actions">

                            {{-- ADD TO CART --}}
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $product->ServiceID }}">

                                <button type="submit" class="add-cart-btn">
                                    <i class="icon-shopping-cart"></i>
                                    Add to Cart
                                </button>

                            </form>


                            {{-- BUY NOW --}}
                            <form method="POST" action="{{ route('cart.buyNow') }}">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $product->ServiceID }}">

                                <button type="submit" class="buy-now-btn">
                                    Buy Now
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center">
                No Hair Products.
            </div>

            @endforelse

        </div>


        <hr class="section-divider">


        {{-- ================================================== --}}
        {{-- SKIN CARE --}}
        {{-- ================================================== --}}

        <div class="text-center mb-5">

            <h2>Skin Care</h2>

            <p>
                Professional Facial Treatments
            </p>

        </div>


        {{-- ===================== --}}
        {{-- SKIN SERVICES --}}
        {{-- ===================== --}}

        <div class="row">

            @forelse($skinServices as $service)

            <div class="col-lg-4 col-md-6 mb-4 d-flex">

                <div class="service-card w-100">

                    <img src="{{ asset('frontend/images/'.$service->Image) }}" class="service-image"
                        alt="{{ $service->ServiceName }}">

                    <div class="service-body text-center">

                        <h4 class="service-title">
                            {{ $service->ServiceName }}
                        </h4>

                        <p class="service-desc">
                            {{ $service->Description }}
                        </p>

                        <div class="service-price">
                            @if($service->activePromotion)
                            <span class="text-muted text-decoration-line-through">
                                {{ number_format($service->Price) }} VND
                            </span>
                            <strong class="text-danger">
                                {{ number_format($service->discounted_price) }} VND
                            </strong>
                            @if($service->activePromotion->DiscountType === 'Percent')
                            <span class="badge bg-danger">
                                -{{ $service->activePromotion->DiscountValue }}%
                            </span>
                            @else
                            <span class="badge bg-danger">
                                -{{ number_format($service->activePromotion->DiscountValue) }} VND
                            </span>
                            @endif
                            @else
                            {{ number_format($service->Price) }} VND
                            @endif
                        </div>

                        <div class="service-time">
                            {{ $service->DurationMinutes }} Minutes
                        </div>

                        {{-- BOOK SERVICE --}}
                        <a href="{{ route('booking', ['service' => $service->ServiceID]) }}"
                            class="btn btn-primary book-btn">
                            Book Now
                        </a>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center">
                No Skin Services.
            </div>

            @endforelse

        </div>


        <hr class="section-divider">


        {{-- ===================== --}}
        {{-- SKIN PRODUCTS --}}
        {{-- ===================== --}}

        <div class="text-center mb-5">

            <h3>Recommended Skin Products</h3>

            <p>
                Products available at ZenStyle.
            </p>

        </div>


        <div class="row">

            @forelse($skinProducts as $product)

            <div class="col-lg-4 col-md-6 mb-4 d-flex">

                <div class="product-card w-100" id="product-{{ $product->ServiceID }}">

                    <img src="{{ asset('frontend/images/'.$product->Image) }}" class="product-image"
                        alt="{{ $product->ServiceName }}">

                    <div class="service-body text-center">

                        <h5 class="product-title">
                            {{ $product->ServiceName }}
                        </h5>

                        <p class="service-desc">
                            {{ $product->Description }}
                        </p>

                        <div class="product-price">
                            @if($product->activePromotion)
                            <span class="text-muted text-decoration-line-through">
                                {{ number_format($product->Price) }} VND
                            </span>
                            <strong class="text-danger">
                                {{ number_format($product->discounted_price) }} VND
                            </strong>
                            @if($product->activePromotion->DiscountType === 'Percent')
                            <span class="badge bg-danger">
                                -{{ $product->activePromotion->DiscountValue }}%
                            </span>
                            @else
                            <span class="badge bg-danger">
                                -{{ number_format($product->activePromotion->DiscountValue) }} VND
                            </span>
                            @endif
                            @else
                            {{ number_format($product->Price) }} VND
                            @endif
                        </div>


                        {{-- PRODUCT ACTIONS --}}
                        <div class="product-actions">

                            {{-- ADD TO CART --}}
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $product->ServiceID }}">

                                <button type="submit" class="add-cart-btn">
                                    <i class="icon-shopping-cart"></i>
                                    Add to Cart
                                </button>

                            </form>


                            {{-- BUY NOW --}}
                            <form method="POST" action="{{ route('cart.buyNow') }}">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $product->ServiceID }}">

                                <button type="submit" class="buy-now-btn">
                                    Buy Now
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center">
                No Skin Products.
            </div>

            @endforelse

        </div>


        <hr class="section-divider">


        {{-- ================================================== --}}
        {{-- MASSAGE --}}
        {{-- ================================================== --}}

        <div class="text-center mb-5">

            <h2>Massage</h2>

            <p>
                Relaxing Body Treatments
            </p>

        </div>


        {{-- ===================== --}}
        {{-- MASSAGE SERVICES --}}
        {{-- ===================== --}}

        <div class="row">

            @forelse($massageServices as $service)

            <div class="col-lg-4 col-md-6 mb-4 d-flex">

                <div class="service-card w-100">

                    <img src="{{ asset('frontend/images/'.$service->Image) }}" class="service-image"
                        alt="{{ $service->ServiceName }}">

                    <div class="service-body text-center">

                        <h4 class="service-title">
                            {{ $service->ServiceName }}
                        </h4>

                        <p class="service-desc">
                            {{ $service->Description }}
                        </p>

                        <div class="service-price">
                            @if($service->activePromotion)
                            <span class="text-muted text-decoration-line-through">
                                {{ number_format($service->Price) }} VND
                            </span>
                            <strong class="text-danger">
                                {{ number_format($service->discounted_price) }} VND
                            </strong>
                            @if($service->activePromotion->DiscountType === 'Percent')
                            <span class="badge bg-danger">
                                -{{ $service->activePromotion->DiscountValue }}%
                            </span>
                            @else
                            <span class="badge bg-danger">
                                -{{ number_format($service->activePromotion->DiscountValue) }} VND
                            </span>
                            @endif
                            @else
                            {{ number_format($service->Price) }} VND
                            @endif
                        </div>

                        <div class="service-time">
                            {{ $service->DurationMinutes }} Minutes
                        </div>

                        {{-- BOOK SERVICE --}}
                        <a href="{{ route('booking', ['service' => $service->ServiceID]) }}"
                            class="btn btn-primary book-btn">
                            Book Now
                        </a>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center">
                No Massage Services.
            </div>

            @endforelse

        </div>


        <hr class="section-divider">


        {{-- ===================== --}}
        {{-- MASSAGE PRODUCTS --}}
        {{-- ===================== --}}

        <div class="text-center mb-5">

            <h3>Recommended Massage Products</h3>

            <p>
                Professional products used during massage treatments.
            </p>

        </div>


        <div class="row">

            @forelse($massageProducts as $product)

            <div class="col-lg-4 col-md-6 mb-4 d-flex">

                <div class="product-card w-100" id="product-{{ $product->ServiceID }}">

                    <img src="{{ asset('frontend/images/'.$product->Image) }}" class="product-image"
                        alt="{{ $product->ServiceName }}">

                    <div class="service-body text-center">

                        <h5 class="product-title">
                            {{ $product->ServiceName }}
                        </h5>

                        <p class="service-desc">
                            {{ $product->Description }}
                        </p>

                        <div class="product-price">
                            @if($product->activePromotion)
                            <span class="text-muted text-decoration-line-through">
                                {{ number_format($product->Price) }} VND
                            </span>
                            <strong class="text-danger">
                                {{ number_format($product->discounted_price) }} VND
                            </strong>
                            @if($product->activePromotion->DiscountType === 'Percent')
                            <span class="badge bg-danger">
                                -{{ $product->activePromotion->DiscountValue }}%
                            </span>
                            @else
                            <span class="badge bg-danger">
                                -{{ number_format($product->activePromotion->DiscountValue) }} VND
                            </span>
                            @endif
                            @else
                            {{ number_format($product->Price) }} VND
                            @endif
                        </div>


                        {{-- PRODUCT ACTIONS --}}
                        <div class="product-actions">

                            {{-- ADD TO CART --}}
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $product->ServiceID }}">

                                <button type="submit" class="add-cart-btn">
                                    <i class="icon-shopping-cart"></i>
                                    Add to Cart
                                </button>

                            </form>


                            {{-- BUY NOW --}}
                            <form method="POST" action="{{ route('cart.buyNow') }}">
                                @csrf

                                <input type="hidden" name="service_id" value="{{ $product->ServiceID }}">

                                <button type="submit" class="buy-now-btn">
                                    Buy Now
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12 text-center">
                No Massage Products.
            </div>

            @endforelse

        </div>

    </div>

</section>

@endsection