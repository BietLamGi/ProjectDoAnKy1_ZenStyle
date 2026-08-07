@extends('layouts.app.app')

@section('title','Booking Success')

@section('content')

<div class="container py-5">

    <div class="text-center">

        <div style="font-size:80px;color:#28a745;">
            ✓
        </div>

        <h2 class="mt-3">
            Appointment Booked Successfully
        </h2>

        <p>
            Thank you for choosing
            <strong>ZenStyle Salon & Spa</strong>
        </p>

        <hr>

        <h4>
            Booking #{{ $appointment->AppointmentID }}
        </h4>

        <br>

        <a href="{{ route('home') }}"
           class="btn btn-outline-primary">

            Back Home

        </a>

    </div>

</div>

@endsection