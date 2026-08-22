@extends('layouts.app.app')

@section('title', 'Order Details')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/appointments.css') }}">
@endsection

@section('content')

<div class="appointments-page">

    {{-- BACK --}}
    <div class="booking-back">
        <a href="{{ route('customer.orders.index') }}">
            <i class="icon-arrow-left"></i>
        </a>
    </div>


    {{-- PAGE HEADER --}}
    <div class="appointment-page-header">

        <h2>Order Details</h2>

        <p>
            Order #{{ $invoice->InvoiceID }}
        </p>

    </div>


    <div class="appointments-list">

        <div class="appointment-card order-detail-card">


            {{-- ORDER HEADER --}}
            <div class="appointment-card-header">

                <span class="appointment-label">
                    ORDER #{{ $invoice->InvoiceID }}
                </span>

                <span class="appointment-status">
                    <i class="icon-record"></i>

                    {{ $invoice->PaymentMethod ?? 'Pending' }}

                </span>

            </div>


            {{-- PRODUCTS --}}
            <div class="appointment-service order-products">

                <div class="order-section-title">
                    <h4>Products</h4>
                </div>


                @forelse ($invoice->details as $detail)

                <div class="service-info order-product-item">


                    {{-- IMAGE --}}
                    <div class="service-icon order-product-image">

                        @if ($detail->service && $detail->service->Image)

                        <img src="{{ asset(
                                    'frontend/images/' .
                                    $detail->service->Image
                                ) }}" alt="{{ $detail->service->ServiceName }}">

                        @else

                        <div class="order-product-placeholder">
                            <i class="icon-picture"></i>
                        </div>

                        @endif

                    </div>


                    {{-- PRODUCT --}}
                    <div class="service-text order-product-info">

                        <h4>
                            {{ $detail->service->ServiceName ?? 'Product' }}
                        </h4>

                        <p>
                            Quantity: {{ $detail->Quantity }}
                        </p>

                        <p>
                            Unit Price:
                            {{ number_format(
                                $detail->UnitPrice,
                                0,
                                ',',
                                '.'
                            ) }}
                            VND
                        </p>

                    </div>


                    {{-- SUBTOTAL --}}
                    <div class="order-product-price">

                        <span>
                            {{ number_format(
                                $detail->UnitPrice *
                                $detail->Quantity,
                                0,
                                ',',
                                '.'
                            ) }}
                            VND
                        </span>

                    </div>

                </div>

                @empty

                <div class="service-info">

                    <div class="service-text">

                        <h4>No products</h4>

                        <p>
                            This order has no items.
                        </p>

                    </div>

                </div>

                @endforelse

            </div>


            {{-- SHIPPING --}}
            <div class="appointment-service order-shipping">

                <div class="order-section-title">
                    <h4>Shipping Information</h4>
                </div>

                <div class="shipping-info-grid">

                    {{-- FULL NAME --}}
                    <div class="shipping-item">

                        <span class="shipping-label">
                            <i class="icon-user"></i>
                            Full Name
                        </span>

                        <span class="shipping-value">
                            {{ $invoice->ShippingName ?? 'N/A' }}
                        </span>

                    </div>


                    {{-- PHONE --}}
                    <div class="shipping-item">

                        <span class="shipping-label">
                            <i class="icon-phone"></i>
                            Phone
                        </span>

                        <span class="shipping-value">
                            {{ $invoice->ShippingPhone ?? 'N/A' }}
                        </span>

                    </div>


                    {{-- ADDRESS --}}
                    <div class="shipping-item shipping-address">

                        <span class="shipping-label">
                            <span class="address-icon">📍</span>
                            Address
                        </span>

                        <span class="shipping-value">
                            {{ $invoice->ShippingAddress ?? 'N/A' }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- ORDER SUMMARY --}}
            <div class="order-summary">

                <div class="order-summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong>
                        {{ number_format(
                            $invoice->TotalAmount,
                            0,
                            ',',
                            '.'
                        ) }}
                        VND
                    </strong>

                </div>


                <div class="order-summary-row">

                    <span>
                        Discount
                    </span>

                    <strong>
                        {{ number_format(
                            $invoice->DiscountAmount,
                            0,
                            ',',
                            '.'
                        ) }}
                        VND
                    </strong>

                </div>


                <div class="order-summary-row order-summary-total">

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

            </div>


            {{-- ORDER DATE --}}
            <div class="appointment-details">

                <div class="detail-item">

                    <div class="detail-icon">
                        <i class="icon-calendar"></i>
                    </div>

                    <div>

                        <span class="detail-label">
                            Order Date
                        </span>

                        <strong>
                            {{ $invoice->InvoiceDate?->format('d M Y H:i') }}
                        </strong>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-icon">
                        <i class="icon-money"></i>
                    </div>

                    <div>

                        <span class="detail-label">
                            Payment
                        </span>

                        <strong>
                            {{ $invoice->PaymentMethod ?? 'Pending' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- BACK --}}
            <div class="appointment-card-footer">

                <a href="{{ route('customer.orders.index') }}" class="appointment-detail-btn">

                    <i class="icon-arrow-left"></i>

                    Back to My Orders

                </a>

            </div>

        </div>

    </div>

</div>

@endsection