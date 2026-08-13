@extends('layouts.app.app')

@section('title', 'ZenStyle Salon & Spa - Home')

@section('content')

{{-- Booking Success Message --}}
@if (session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

<!-- ============ HOME / HERO ============ -->
<div id="home" class="hero-wrap js-fullheight" style="background-image: url('{{ asset('frontend/images/spa1.jpg') }}');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center" data-animate-effect="fadeInUp">
                <h1 class="mb-3">ZenStyle Salon &amp; Spa</h1>
                <p class="mb-4">
                    A place where relaxation meets beauty. Let our experienced professionals
                    provide you with exceptional beauty and wellness services.
                </p>
                <p>
                    <a href="{{ route('booking') }}" class="btn btn-primary px-4 py-3 mr-2">Book Now</a>
                    <a href="{{ route('services') }}" class="btn btn-white btn-outline-white px-4 py-3">View
                        Services</a>
                </p>
            </div>
        </div>
    </div>
</div>



<!-- ============ ABOUT ============ -->
<section id="about" class="ftco-section">
    <div class="container">
        <div class="row d-flex">

            <div class="col-md-6 d-flex align-items-stretch ftco-animate" data-animate-effect="fadeInLeft">
                <div class="img img-about align-self-stretch" style="background-image: url('{{ asset('frontend/images/skin3.jpg') }}');
                            min-height: 420px; width: 100%; border-radius: 6px;">
                </div>
            </div>

            <div class="col-md-6 pl-md-5 py-5 ftco-animate" data-animate-effect="fadeInRight">
                <div class="heading-section">
                    <span class="subheading">About Us</span>
                    <h2 class="mb-4">Who We Are</h2>
                </div>

                <p>
                    ZenStyle is a professional salon and spa dedicated to enhancing your
                    beauty and well-being. We combine expert techniques with a relaxing
                    atmosphere to create an unforgettable experience.
                </p>

                <p>
                    Our experienced specialists, modern equipment, and premium beauty
                    products ensure safe and effective treatments for every customer.
                </p>

                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Professional beauty experts</li>
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Premium and authentic products
                    </li>
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Luxury and private environment
                    </li>
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Fast and convenient online
                        booking</li>
                </ul>

                <p class="mt-4">
                    <a href="{{ route('services') }}" class="btn btn-primary px-4 py-3">
                        Explore Services
                    </a>
                </p>
            </div>

        </div>
    </div>
</section>


<!-- ============ COUNTER ============ -->
<section id="section-counter" class="ftco-counter img"
    style="background-image: url('{{ asset('frontend/images/counter-hair.jpg') }}');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row d-flex justify-content-center">

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="flaticon-cosmetics"></span></div>
                    <div class="text">
                        <strong class="number" data-number="8">0</strong>
                        <span>Years of Experience</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="icon-users"></span></div>
                    <div class="text">
                        <strong class="number" data-number="3200">0</strong>
                        <span>Happy Customers</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="icon-user"></span></div>
                    <div class="text">
                        <strong class="number" data-number="15">0</strong>
                        <span>Beauty Specialists</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="icon-star"></span></div>
                    <div class="text">
                        <strong class="number" data-number="20">0</strong>
                        <span>Beauty Services</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<!-- ============ HOT SERVICES ============ -->
<section class="ftco-section bg-light">

    <div class="container">

        <div class="row justify-content-center mb-5">

            <div class="col-lg-8 text-center heading-section">

                <span class="subheading">

                    Professional Beauty Care

                </span>

                <h2>

                    Popular Categories

                </h2>

                <p>

                    Choose your favorite beauty service.

                </p>

            </div>

        </div>

        <div class="row">

            <!-- Hair -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="home-service-card">

                    <div class="home-image">

                        <img src="{{ asset('frontend/images/hot-hair.png') }}" alt="Hair">

                    </div>

                    <div class="home-content">

                        <div class="home-icon">

                            <span class="icon-cut"></span>

                        </div>

                        <h3>

                            Hair Salon

                        </h3>

                        <p>

                            Hair Cut • Coloring • Perm • Straightening • Treatment • Hair Extension

                        </p>

                        <a href="{{ route('services') }}" class="btn btn-primary">

                            View Services

                        </a>

                    </div>

                </div>

            </div>

            <!-- Skin -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="home-service-card">

                    <div class="home-image">

                        <img src="{{ asset('frontend/images/hot-skin.jpg') }}" alt="Skin">

                    </div>

                    <div class="home-content">

                        <div class="home-icon">

                            <span class="flaticon-cosmetics"></span>

                        </div>

                        <h3>

                            Skin Care

                        </h3>

                        <p>

                            Acne Treatment • CO2 Detox • Peel • Fractional CO2 • Meso Exosome

                        </p>

                        <a href="{{ route('services') }}" class="btn btn-primary">

                            View Services

                        </a>

                    </div>

                </div>

            </div>

            <!-- Massage -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="home-service-card">

                    <div class="home-image">

                        <img src="{{ asset('frontend/images/hot-spa.jpg') }}" alt="Massage">

                    </div>

                    <div class="home-content">

                        <div class="home-icon">

                            <span class="icon-heart"></span>

                        </div>

                        <h3>

                            Massage

                        </h3>

                        <p>

                            Body Massage • Neck & Shoulder • Head Spa • Herbal Shampoo

                        </p>

                        <a href="{{ route('services') }}" class="btn btn-primary">

                            View Services

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection