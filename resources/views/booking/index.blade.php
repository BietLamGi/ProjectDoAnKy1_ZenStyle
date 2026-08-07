@extends('layouts.app.app')

@section('title', 'Book Appointment')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/css/booking.css') }}">
@endsection

<!-- ============ BOOKING ============ -->
<section id="booking" class="booking-section">

    <div class="booking-container">

        <!-- Header -->
        <div class="booking-header">
            <h2>BOOK APPOINTMENT</h2>
            <p>Relax • Refresh • Renew</p>
        </div>

         @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

        @endif

        <form class="booking-form"
              method="POST"
              action="{{ route('booking.store') }}">
            @csrf

            <!-- Full Name -->
            <div class="booking-field">
                <label>Full Name</label>
                <input type="text"
                       name="fullname"
                       value="{{ old('fullname') }}"
                       class="@error('fullname') input-error @enderror"
                       placeholder="Enter your full name">

                @error('fullname')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <!-- Phone -->
            <div class="booking-field">
                <label>Phone Number</label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Enter your phone number">

                @error('phone')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <!-- Service -->
            <div class="row">

    <div class="col-md-6">

        <div class="booking-field">

            <label>Select Service</label>

            <select name="service" class="form-control">

                <option value="">
                    -- Select Service --
                </option>

                @foreach($services as $service)

                    <option
                        value="{{ $service->ServiceID }}"
                        {{ old('service')==$service->ServiceID?'selected':'' }}>

                        {{ $service->Category }}
                        -
                        {{ $service->ServiceName }}

                    </option>

                @endforeach

            </select>

        </div>

    </div>

    <div class="col-md-6">

        <div class="booking-field">

            <label>Appointment Date</label>

            <input
                type="date"
                name="appointment_date"
                class="form-control"
                value="{{ old('appointment_date') }}"
                min="{{ date('Y-m-d') }}">

        </div>

    </div>

</div>

            <!-- Time -->
            <div class="booking-field">
                <label>Choose Time</label>

                <div class="time-options">

                    @foreach(['09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00','18:00'] as $time)

                        <label class="time-item">
                            <input type="radio"
                                   name="appointment_time"
                                   value="{{ $time }}"
                                   {{ old('appointment_time') == $time ? 'checked' : '' }}>

                            <span>{{ $time }}</span>
                        </label>

                    @endforeach

                </div>
            </div>

            <!-- Notes -->
            <div class="booking-field">
                <label>Additional Notes</label>

                <textarea name="note"
                          rows="4"
                          placeholder="Anything you'd like us to know?">{{ old('note') }}</textarea>
            </div>

            <!-- Button -->
            <button type="submit" class="booking-submit">
                BOOK APPOINTMENT
            </button>

        </form>

    </div>

</section>
