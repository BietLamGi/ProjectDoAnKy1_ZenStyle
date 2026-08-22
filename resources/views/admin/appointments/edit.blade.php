@extends('layouts.admin.admin')
@section('content')

<div class="page-header">
    <div>
        <h1>Edit Appointment</h1>
        <p>Update appointment information</p>
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
        <strong>Appointment #{{ $appointment->AppointmentID }}</strong>
    </div>

    <form
        action="{{ route('appointments.update', $appointment->AppointmentID) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

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
                            {{ old('CustomerID', $appointment->CustomerID) == $customer->CustomerID ? 'selected' : '' }}
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
                            {{ old('StaffID', $appointment->StaffID) == $member->UserID ? 'selected' : '' }}
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
                    value="{{ old('AppointmentDate', optional($appointment->AppointmentDate)->format('Y-m-d')) }}"
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
                    value="{{ old('StartTime', substr($appointment->StartTime, 0, 5)) }}"
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
                    value="{{ old('EndTime', substr($appointment->EndTime, 0, 5)) }}"
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
                        {{ old('Status', $appointment->Status) == 'Booked' ? 'selected' : '' }}>
                        Booked
                    </option>

                    <option value="Confirmed"
                        {{ old('Status', $appointment->Status) == 'Confirmed' ? 'selected' : '' }}>
                        Confirmed
                    </option>

                    <option value="Completed"
                        {{ old('Status', $appointment->Status) == 'Completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="Cancelled"
                        {{ old('Status', $appointment->Status) == 'Cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                    <option value="No-show"
                        {{ old('Status', $appointment->Status) == 'No-show' ? 'selected' : '' }}>
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
                >{{ old('Notes', $appointment->Notes) }}</textarea>
            </div>

        </div>

        <div class="form-actions">

            <a
                href="{{ route('appointments.index') }}"
                class="btn-secondary"
            >
                Cancel
            </a>

            <button type="submit" class="btn-primary">
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection