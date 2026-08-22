@extends('layouts.app.app')

@section('title', 'Appointment Details')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/appointment-detail.css') }}">
@endsection

@section('content')

<div class="appointment-detail-page">

    {{-- HEADER --}}
    <div class="appointment-detail-heading">

        <!-- <span class="detail-eyebrow">
            ZENSTYLE SALON & SPA
        </span> -->

        <h2>Appointment Details</h2>

        <p>
            Thank you for choosing ZenStyle.
        </p>

    </div>


    {{-- DETAIL CARD --}}
    <div class="booking-detail-card">

        {{-- CARD HEADER --}}
        <div class="booking-detail-top">

            <div>
                <span class="booking-small-title">
                    BOOKING ID
                </span>

                <h3>
                    #{{ $appointment->AppointmentID }}
                </h3>
            </div>

            <span class="booking-status">
                <i class="icon-record"></i>
                {{ $appointment->Status }}
            </span>

        </div>


        {{-- DATE + TIME --}}
        <div class="booking-date-time">

            <div class="booking-info-box">

                <div class="booking-info-icon">
                    <i class="icon-calendar"></i>
                </div>

                <div>
                    <span>Appointment Date</span>

                    <strong>
                        {{ \Carbon\Carbon::parse($appointment->AppointmentDate)->format('d M Y') }}
                    </strong>
                </div>

            </div>


            <div class="booking-info-box">

                <div class="booking-info-icon">
                    <i class="icon-clock-o"></i>
                </div>

                <div>
                    <span>Appointment Time</span>

                    <strong>
                        {{ substr($appointment->StartTime, 0, 5) }}
                        -
                        {{ substr($appointment->EndTime, 0, 5) }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- SERVICE --}}
        <div class="booking-service-section">

            <div class="booking-section-title">

                <i class="icon-scissors"></i>

                <h4>Your Service</h4>

            </div>


            @foreach ($appointment->services as $appointmentService)

            <div class="booking-service-item">

                <div class="booking-service-left">

                    <div class="booking-service-icon">
                        <i class="icon-scissors"></i>
                    </div>

                    <div>

                        <h3>
                            {{ $appointmentService->service->ServiceName }}
                        </h3>

                        <p>
                            {{ $appointmentService->service->Category }}
                        </p>

                        @if($appointmentService->service->DurationMinutes)

                        <small>
                            <i class="icon-clock-o"></i>

                            {{ $appointmentService->service->DurationMinutes }}
                            minutes
                        </small>

                        @endif

                    </div>

                </div>


                <div class="booking-service-price">

                    <span>Price</span>

                    <strong>
                        {{ number_format($appointmentService->UnitPrice) }}
                        <small>VND</small>
                    </strong>

                </div>

            </div>

            @endforeach

        </div>


        {{-- NOTES --}}
        @if ($appointment->Notes)

        <div class="booking-notes">

            <div class="booking-section-title">

                <i class="icon-edit"></i>

                <h4>Additional Notes</h4>

            </div>

            <p>
                {{ $appointment->Notes }}
            </p>

        </div>

        @endif


        {{-- TOTAL --}}
        <div class="booking-total">

            <span>Total</span>

            <strong>
                {{ number_format($appointment->services->sum('UnitPrice')) }}
                <small>VND</small>
            </strong>

        </div>


        {{-- FOOTER --}}
        <div class="booking-detail-footer">

            <a href="{{ route('appointments.my') }}" class="back-appointments-btn">

                <i class="icon-arrow-left"></i>

                Back to My Appointments

            </a>


            @if ($appointment->Status === 'Pending')

            <form method="POST" action="{{ route('customer.appointments.cancel', $appointment->AppointmentID) }}"
                onsubmit="return confirm('Are you sure you want to cancel this appointment?');" class="cancel-form">

                @csrf

                <button type="submit" class="cancel-appointment-btn">
                    Cancel appointment
                </button>

            </form>

            @endif

        </div>

    </div>

</div>

@endsection