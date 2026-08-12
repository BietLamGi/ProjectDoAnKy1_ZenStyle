@extends('layouts.receptionist.app')

@section('title', 'New appointment')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Create walk-in appointment</h1>
            <p class="text-muted mb-0">Use this form for walk-in customers or phone bookings.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.appointments.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Existing customer (optional)</label>
                    <select name="customer_id" class="form-control" id="customerSelect">
                        <option value="">-- New customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->CustomerID }}" @selected(old('customer_id', request('customer_id')) == $customer->CustomerID)>
                                {{ $customer->FullName }} - {{ $customer->Phone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"></div>

                <div class="col-md-6">
                    <label class="form-label">New customer name</label>
                    <input type="text" name="fullname" class="form-control" value="{{ old('fullname') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Service <span class="text-danger">*</span></label>
                    <select name="service_id" class="form-control" required>
                        <option value="">-- Select service --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->ServiceID }}" @selected(old('service_id') == $service->ServiceID)>
                                {{ $service->Category }} - {{ $service->ServiceName }} ({{ number_format($service->Price, 0, ',', '.') }}đ, {{ $service->DurationMinutes }} minutes)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Appointment date <span class="text-danger">*</span></label>
                    <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Appointment time <span class="text-danger">*</span></label>
                    <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create appointment
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
