@extends('layouts.app.app')

@section('title', 'Track Order & Appointment')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/track-order.css') }}">
@endsection

@section('content')

<div class="track-page">

    <div class="container">

        {{-- HEADER --}}
        <div class="track-header">

            <span class="track-subtitle">
                ZENSTYLE SALON & SPA
            </span>

            <h2>
                Track Your Order
            </h2>

            <p>
                Check your orders and appointments using your phone number.
            </p>

        </div>


        {{-- SEARCH FORM --}}
        <div class="track-search-card">

            <form method="POST" action="{{ route('track-order.search') }}">

                @csrf

                <div class="track-form-group">

                    <label for="phone">
                        Phone Number
                    </label>

                    <input type="text" id="phone" name="phone" value="{{ $phone ?? old('phone') }}"
                        placeholder=" Enter your phone number" autocomplete="off" required>

                    @error('phone')
                    <small class="track-error">
                        {{ $message }}
                    </small>
                    @enderror

                </div>

                <button type="submit" class="track-submit">
                    Track Now
                    <i class="icon-search"></i>
                </button>

            </form>

        </div>


        {{-- ERROR / NOT FOUND --}}
        @if(session('error'))

        <div class="track-alert">
            <i class="icon-warning"></i>

            <span>
                {{ session('error') }}
            </span>
        </div>

        @endif


        {{-- SEARCH RESULT --}}
        @isset($searched)

        @if($searched)

        {{-- RESULT HEADER --}}
        <div class="track-result-header">

            <div>
                <span>SEARCH RESULT</span>

                <h3>
                    Your History
                </h3>
            </div>

            <a href="{{ route('track-order.index') }}">
                Search Again
            </a>

        </div>


        {{-- ========================= --}}
        {{-- ORDERS --}}
        {{-- ========================= --}}

        <section class="track-section">

            <div class="track-section-title">

                <div>
                    <span>01</span>

                    <h3>
                        My Orders
                    </h3>
                </div>

                <small>
                    {{ $invoices->count() }} order(s)
                </small>

            </div>


            @forelse($invoices as $invoice)

            <div class="track-order-card">

                {{-- ORDER HEADER --}}
                <div class="track-order-top">

                    <div>

                        <span class="track-label">
                            ORDER #{{ $invoice->InvoiceID }}
                        </span>

                        <h4>
                            Order Information
                        </h4>

                    </div>

                    <span class="track-payment">
                        {{ $invoice->PaymentMethod ?? 'Pending' }}
                    </span>

                </div>


                {{-- PRODUCTS --}}
                <div class="track-products">

                    @forelse($invoice->details as $detail)

                    <div class="track-product">

                        <div class="track-product-image">

                            @if($detail->service && $detail->service->Image)

                            <img src="{{ asset('frontend/images/'.$detail->service->Image) }}"
                                alt="{{ $detail->service->ServiceName }}">

                            @else

                            <div class="track-no-image">
                                <i class="icon-shopping-cart"></i>
                            </div>

                            @endif

                        </div>


                        <div class="track-product-info">

                            <h5>
                                {{ $detail->service->ServiceName ?? 'Product' }}
                            </h5>

                            <span>
                                Quantity: {{ $detail->Quantity }}
                            </span>

                        </div>


                        <strong class="track-product-price">
                            {{ number_format(
                                                $detail->UnitPrice * $detail->Quantity,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                            VND
                        </strong>

                    </div>

                    @empty

                    <p class="track-empty-text">
                        No products in this order.
                    </p>

                    @endforelse

                </div>


                {{-- ORDER FOOTER --}}
                <div class="track-order-bottom">

                    <div class="track-order-date">

                        <span>
                            Order Date
                        </span>

                        <strong>
                            {{ $invoice->InvoiceDate?->format('d M Y') }}
                        </strong>

                    </div>


                    <div class="track-order-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            {{ number_format(
                                            $invoice->FinalAmount,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                            VND
                        </strong>

                    </div>


                    <a href="{{ route(
                                        'customer.orders.show',
                                        $invoice->InvoiceID
                                    ) }}" class="track-detail-btn">
                        View Details
                        <i class="icon-arrow-right"></i>
                    </a>

                </div>

            </div>

            @empty

            <div class="track-empty">
                <h4>
                    No Orders Found
                </h4>

                <p>
                    We couldn't find any orders for this phone number.
                </p>
            </div>

            @endforelse

        </section>


        {{-- ========================= --}}
        {{-- APPOINTMENTS --}}
        {{-- ========================= --}}

        <section class="track-section">

            <div class="track-section-title">

                <div>
                    <span>02</span>

                    <h3>
                        My Appointments
                    </h3>
                </div>

                <small>
                    {{ $appointments->count() }} appointment(s)
                </small>

            </div>


            @forelse($appointments as $appointment)

            <div class="track-appointment-card">

                <div class="track-appointment-main">

                    <div class="track-appointment-image">

                        @if($appointment->service && $appointment->service->Image)

                        <img src="{{ asset(
                                                'frontend/images/'.$appointment->service->Image
                                            ) }}" alt="{{ $appointment->service->ServiceName }}">

                        @else

                        <div class="track-no-image">
                            <i class="icon-calendar"></i>
                        </div>

                        @endif

                    </div>


                    <div class="track-appointment-info">

                        <span class="track-label">
                            APPOINTMENT #{{ $appointment->AppointmentID }}
                        </span>

                        <h4>
                            {{ $appointment->service->ServiceName
                                            ?? 'Appointment' }}
                        </h4>

                        <p>
                            {{ $appointment->Customer->FullName
                                            ?? '' }}
                        </p>

                    </div>

                </div>


                <div class="track-appointment-details">

                    <div>
                        <span>Date</span>

                        <strong>
                            {{ \Carbon\Carbon::parse(
                                            $appointment->AppointmentDate
                                        )->format('d M Y') }}
                        </strong>
                    </div>


                    <div>
                        <span>Time</span>

                        <strong>
                            {{ \Carbon\Carbon::parse($appointment->StartTime)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($appointment->EndTime)->format('H:i') }}
                        </strong>
                    </div>


                    <div>
                        <span>Status</span>

                        <strong class="track-status">
                            {{ $appointment->Status ?? 'Pending' }}
                        </strong>
                    </div>

                </div>

            </div>

            @empty

            <div class="track-empty">

                <h4>
                    No Appointments Found
                </h4>

                <p>
                    We couldn't find any appointments for this phone number.
                </p>

            </div>

            @endforelse

        </section>

        @endif

        @endisset

    </div>

</div>

@endsection