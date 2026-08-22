@extends('layouts.app.app')

@section('title', 'My Appointments')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/appointments.css') }}">
@endsection

@section('content')

<div class="appointments-page">
    <div class="booking-back">
        <a href="{{ route('profile') }}">
            <i class="icon-arrow-left"></i>
        </a>
    </div>

    {{-- PAGE HEADER --}}
    <div class="appointment-page-header">

        <!-- <span class="appointment-eyebrow">
            ZENSTYLE SALON & SPA
        </span> -->

        <h2>My Appointments</h2>

        <p>
            View and manage your appointments.
        </p>

    </div>


    {{-- APPOINTMENTS --}}
    <div class="appointments-list">

        @forelse ($appointments as $appointment)

        <div class="appointment-card">

            <div class="appointments-back">

            </div>

            {{-- HEADER --}}
            <div class="appointment-card-header">

                <span class="appointment-label">
                    APPOINTMENT #{{ $appointment->AppointmentID }}
                </span>

                <span class="appointment-status">
                    <i class="icon-record"></i>
                    {{ $appointment->Status }}
                </span>

            </div>


            {{-- SERVICE --}}
            <div class="appointment-service">

                @foreach ($appointment->services as $appointmentService)

                <div class="service-info">

                    <div class="service-icon">
                        <i class="icon-scissors"></i>
                    </div>

                    <div class="service-text">

                        <h4>
                            {{ $appointmentService->service->ServiceName }}
                        </h4>

                        <p>
                            {{ $appointmentService->service->Category }}
                        </p>

                    </div>

                </div>

                @endforeach

            </div>


            {{-- DATE + TIME --}}
            <div class="appointment-details">

                <div class="detail-item">

                    <div class="detail-icon">
                        <i class="icon-calendar"></i>
                    </div>

                    <div>
                        <span class="detail-label">
                            Appointment Date
                        </span>

                        <strong>
                            {{ \Carbon\Carbon::parse($appointment->AppointmentDate)->format('d M Y') }}
                        </strong>
                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-icon">
                        <i class="icon-clock-o"></i>
                    </div>

                    <div>
                        <span class="detail-label">
                            Time
                        </span>

                        <strong>
                            {{ substr($appointment->StartTime, 0, 5) }}
                            -
                            {{ substr($appointment->EndTime, 0, 5) }}
                        </strong>
                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="appointment-card-footer">

                <a href="{{ route('customer.appointments.show', $appointment->AppointmentID) }}"
                    class="appointment-detail-btn">

                    View Details

                    <i class="icon-arrow-right"></i>

                </a>

            </div>

        </div>

        @empty

        <div class="empty-appointments">

            <div class="empty-icon">
                <i class="icon-calendar"></i>
            </div>

            <h3>No Appointments Yet</h3>

            <p>
                You haven't booked any appointments with us yet.
            </p>

            <a href="{{ route('booking') }}" class="book-appointment-btn">

                <i class="icon-calendar"></i>
                Book Appointment

            </a>

        </div>

        @endforelse

    </div>

</div>

@endsection