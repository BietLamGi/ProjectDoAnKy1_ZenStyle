@extends('layouts.app.app')

@section('title', 'My Orders')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/appointments.css') }}">
@endsection

@section('content')

<div class="appointments-page">

    {{-- BACK TO PROFILE --}}
    <div class="booking-back">
        <a href="{{ route('profile') }}">
            <i class="icon-arrow-left"></i>
        </a>
    </div>


    {{-- PAGE HEADER --}}
    <div class="appointment-page-header">

        <h2>My Orders</h2>

        <p>
            View your purchase and payment history.
        </p>

    </div>


    {{-- ORDERS --}}
    <div class="appointments-list">

        @forelse ($invoices as $invoice)

        <div class="appointment-card order-card">

            {{-- HEADER --}}
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

                @forelse ($invoice->details as $detail)

                <div class="service-info order-product-item">

                    {{-- PRODUCT IMAGE --}}
                    <div class="service-icon order-product-image">

                        @if ($detail->service && $detail->service->Image)

                        <img src="{{ asset('frontend/images/' . $detail->service->Image) }}"
                            alt="{{ $detail->service->ServiceName }}">

                        @else

                        <div class="order-product-placeholder">
                            <i class="icon-picture"></i>
                        </div>

                        @endif

                    </div>


                    {{-- PRODUCT INFORMATION --}}
                    <div class="service-text order-product-info">

                        <h4>
                            {{ $detail->service->ServiceName ?? 'Product' }}
                        </h4>

                        <p>
                            Quantity: {{ $detail->Quantity }}
                        </p>

                    </div>


                    {{-- PRICE --}}
                    <div class="order-product-price">

                        <span>
                            {{ number_format(
                                $detail->UnitPrice,
                                0,
                                ',',
                                '.'
                            ) }} VND
                        </span>

                    </div>

                </div>

                @empty

                <div class="service-info">

                    <div class="service-text">

                        <h4>
                            No products
                        </h4>

                        <p>
                            This order has no items.
                        </p>

                    </div>

                </div>

                @endforelse

            </div>


            {{-- ORDER DATE + TOTAL --}}
            <div class="appointment-details">

                {{-- DATE --}}
                <div class="detail-item">

                    <div class="detail-icon">
                        <i class="icon-calendar"></i>
                    </div>

                    <div>

                        <span class="detail-label">
                            Order Date
                        </span>

                        <strong>
                            {{ $invoice->InvoiceDate?->format('d M Y') }}
                        </strong>

                    </div>

                </div>


                {{-- TOTAL --}}
                <div class="detail-item">

                    <div class="detail-icon">
                        <i class="icon-money"></i>
                    </div>

                    <div>

                        <span class="detail-label">
                            Total
                        </span>

                        <strong class="order-total">
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

            </div>


            {{-- FOOTER --}}
            <div class="appointment-card-footer">

                <a href="{{ route(
                        'customer.orders.show',
                        $invoice->InvoiceID
                    ) }}" class="appointment-detail-btn">

                    View Details

                    <i class="icon-arrow-right"></i>

                </a>

            </div>

        </div>

        @empty

        {{-- EMPTY STATE --}}
        <div class="empty-appointments">

            <div class="empty-icon">
                <i class="icon-shopping-cart"></i>
            </div>

            <h3>
                No Orders Yet
            </h3>

            <p>
                You haven't placed any orders with us yet.
            </p>

            <a href="{{ route('services') }}" class="book-appointment-btn">

                <i class="icon-shopping-cart"></i>

                Explore Products

            </a>

        </div>

        @endforelse

    </div>

</div>

@endsection