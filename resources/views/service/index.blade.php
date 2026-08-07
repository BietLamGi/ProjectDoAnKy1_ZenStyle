@extends('layouts.app.app')

@section('title', 'Our Services')

@section('content')

<section class="service-section">

<div class="container">

<div class="text-center mb-5">

<h1>Our Services</h1>

<p>
Discover professional Hair Salon, Skin Care and Massage services.
</p>

</div>

<!-- ===================== -->
<!-- HAIR SERVICES -->
<!-- ===================== -->

<div class="text-center mb-5">

<h2>Hair Salon</h2>

<p>Professional Hair Care Services</p>

</div>

<div class="row">

@forelse($hairServices as $service)

<div class="col-lg-4 col-md-6 mb-4 d-flex">

<div class="service-card w-100">

<img
src="{{ asset('frontend/images/'.$service->Image) }}"
class="service-image"
alt="{{ $service->ServiceName }}">

<div class="service-body text-center">

<h4 class="service-title">

{{ $service->ServiceName }}

</h4>

<p class="service-desc">

{{ $service->Description }}

</p>

<div class="service-price">

{{ number_format($service->Price) }} VND

</div>

<div class="service-time">

{{ $service->DurationMinutes }} Minutes

</div>

<a
href="{{ route('booking') }}"
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

<!-- ===================== -->
<!-- HAIR PRODUCTS -->
<!-- ===================== -->

<div class="text-center mb-5">

<h3>Recommended Hair Products</h3>

<p>Professional products used in our salon.</p>

</div>

<div class="row">

@foreach($hairProducts as $product)

<div class="col-lg-4 col-md-6 mb-4 d-flex">

<div class="product-card w-100">

<img
src="{{ asset('frontend/images/'.$product->Image) }}"
class="product-image"
alt="{{ $product->ServiceName }}">

<div class="service-body text-center">

<h5 class="product-title">

{{ $product->ServiceName }}

</h5>

<p class="service-desc">

{{ $product->Description }}

</p>

<div class="product-price">

{{ number_format($product->Price) }} VND

</div>

</div>

</div>

</div>

@endforeach

</div>

<hr class="section-divider">
<!-- ===================== -->
<!-- SKIN SERVICES -->
<!-- ===================== -->

<div class="text-center mb-5">

    <h2>Skin Care</h2>

    <p>Professional Facial Treatments</p>

</div>

<div class="row">

@forelse($skinServices as $service)

<div class="col-lg-4 col-md-6 mb-4 d-flex">

<div class="service-card w-100">

<img
src="{{ asset('frontend/images/'.$service->Image) }}"
class="service-image"
alt="{{ $service->ServiceName }}">

<div class="service-body text-center">

<h4 class="service-title">

{{ $service->ServiceName }}

</h4>

<p class="service-desc">

{{ $service->Description }}

</p>

<div class="service-price">

{{ number_format($service->Price) }} VND

</div>

<div class="service-time">

{{ $service->DurationMinutes }} Minutes

</div>

<a
href="{{ route('booking') }}"
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

<!-- ===================== -->
<!-- SKIN PRODUCTS -->
<!-- ===================== -->

<div class="text-center mb-5">

<h3>Recommended Skin Products</h3>

<p>Products available at ZenStyle.</p>

</div>

<div class="row">

@foreach($skinProducts as $product)

<div class="col-lg-4 col-md-6 mb-4 d-flex">

<div class="product-card w-100">

<img
src="{{ asset('frontend/images/'.$product->Image) }}"
class="product-image"
alt="{{ $product->ServiceName }}">

<div class="service-body text-center">

<h5 class="product-title">

{{ $product->ServiceName }}

</h5>

<p class="service-desc">

{{ $product->Description }}

</p>

<div class="product-price">

{{ number_format($product->Price) }} VND

</div>

</div>

</div>

</div>

@endforeach

</div>

<hr class="section-divider">

<!-- ===================== -->
<!-- MASSAGE SERVICES -->
<!-- ===================== -->

<div class="text-center mb-5">

<h2>Massage</h2>

<p>Relaxing Body Treatments</p>

</div>

<div class="row">

@forelse($massageServices as $service)

<div class="col-lg-4 col-md-6 mb-4 d-flex">

<div class="service-card w-100">

<img
src="{{ asset('frontend/images/'.$service->Image) }}"
class="service-image"
alt="{{ $service->ServiceName }}">

<div class="service-body text-center">

<h4 class="service-title">

{{ $service->ServiceName }}

</h4>

<p class="service-desc">

{{ $service->Description }}

</p>

<div class="service-price">

{{ number_format($service->Price) }} VND

</div>

<div class="service-time">

{{ $service->DurationMinutes }} Minutes

</div>

<a
href="{{ route('booking') }}"
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

<!-- ===================== -->
<!-- MASSAGE PRODUCTS -->
<!-- ===================== -->

<div class="text-center mb-5">

    <h3>Recommended Massage Products</h3>

    <p>Professional products used during massage treatments.</p>

</div>

<div class="row">

@foreach($massageProducts as $product)

<div class="col-lg-4 col-md-6 mb-4 d-flex">

<div class="product-card w-100">

<img
src="{{ asset('frontend/images/'.$product->Image) }}"
class="product-image"
alt="{{ $product->ServiceName }}">

<div class="service-body text-center">

<h5 class="product-title">

{{ $product->ServiceName }}

</h5>

<p class="service-desc">

{{ $product->Description }}

</p>

<div class="product-price">

{{ number_format($product->Price) }} VND

</div>

</div>

</div>

</div>

@endforeach

</div>

</section>

@endsection