@extends('layouts.admin.admin')
@section('content')

<div class="page-header">
    <div>
        <h1>Create Appointment</h1>
        <p>Create a new salon appointment</p>
    </div>

    <a href="{{ route('appointments.index') }}" class="btn-secondary">
        ← Back
    </a>
</div>

@if ($errors->any())
    <div class="alert-danger">
        <strong>Please fix the following errors:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-header">
        <strong>Appointment Information</strong>
    </div>

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        <div class="form-grid">

            {{-- Customer --}}
            <div class="form-group">
                <label for="CustomerID">
                    Customer <span class="required">*</span>
                </label>

                <select name="CustomerID" id="CustomerID" required>
                    <option value="">-- Select Customer --</option>

                    @foreach ($customers as $customer)
                        <option
                            value="{{ $customer->CustomerID }}"
                            {{ old('CustomerID') == $customer->CustomerID ? 'selected' : '' }}
                        >
                            {{ $customer->FullName }}
                            - {{ $customer->Phone }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Staff --}}
            <div class="form-group">
                <label for="StaffID">
                    Staff
                </label>

                <select name="StaffID" id="StaffID">
                    <option value="">-- No staff assigned --</option>

                    @foreach ($staff as $member)
                        <option
                            value="{{ $member->UserID }}"
                            {{ old('StaffID') == $member->UserID ? 'selected' : '' }}
                        >
                            {{ $member->Username }}
                            @if($member->Position)
                                - {{ $member->Position }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Appointment Date --}}
            <div class="form-group">
                <label for="AppointmentDate">
                    Appointment Date <span class="required">*</span>
                </label>

                <input
                    type="date"
                    name="AppointmentDate"
                    id="AppointmentDate"
                    value="{{ old('AppointmentDate') }}"
                    required
                >
            </div>

            {{-- Start Time --}}
            <div class="form-group">
                <label for="StartTime">
                    Start Time <span class="required">*</span>
                </label>

                <input
                    type="time"
                    name="StartTime"
                    id="StartTime"
                    value="{{ old('StartTime') }}"
                    required
                >
            </div>

            {{-- End Time --}}
            <div class="form-group">
                <label for="EndTime">
                    End Time <span class="required">*</span>
                </label>

                <input
                    type="time"
                    name="EndTime"
                    id="EndTime"
                    value="{{ old('EndTime') }}"
                    required
                >
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="Status">
                    Status <span class="required">*</span>
                </label>

                <select name="Status" id="Status" required>
                    <option value="Booked"
                        {{ old('Status', 'Booked') == 'Booked' ? 'selected' : '' }}>
                        Booked
                    </option>

                    <option value="Confirmed"
                        {{ old('Status') == 'Confirmed' ? 'selected' : '' }}>
                        Confirmed
                    </option>

                    <option value="Completed"
                        {{ old('Status') == 'Completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="Cancelled"
                        {{ old('Status') == 'Cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                    <option value="No-show"
                        {{ old('Status') == 'No-show' ? 'selected' : '' }}>
                        No-show
                    </option>
                </select>
            </div>

            {{-- Notes --}}
            <div class="form-group full-width">
                <label for="Notes">
                    Notes
                </label>

                <textarea
                    name="Notes"
                    id="Notes"
                    rows="4"
                    maxlength="500"
                    placeholder="Enter appointment notes..."
                >{{ old('Notes') }}</textarea>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('appointments.index') }}" class="btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn-primary">
                + Create Appointment
            </button>
        </div>

    </form>

</div>

@endsection