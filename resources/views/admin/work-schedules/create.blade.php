@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold">Edit Work Schedule</h1>
        <p class="text-muted">Update staff work schedule</p>
    </div>

    <a
        href="{{ route('work-schedules.index') }}"
        class="btn btn-secondary"
    >
        ← Back to Work Schedule
    </a>

</div>

@if ($errors->any())

    <div class="alert alert-danger">

        <strong>Please fix the following errors:</strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

@endif

<div class="card">

    <div class="card-body">

        <form
            action="{{ route('work-schedules.update', $workSchedule->ScheduleID) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="row">

                {{-- Staff --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Staff
                    </label>

                    <select
                        name="UserID"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Staff
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->UserID }}"
                                {{ old('UserID', $workSchedule->UserID) == $user->UserID ? 'selected' : '' }}
                            >
                                {{ $user->Username }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Work Date --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Work Date
                    </label>

                    <input
                        type="date"
                        name="WorkDate"
                        class="form-control"
                        value="{{ old('WorkDate', $workSchedule->WorkDate?->format('Y-m-d')) }}"
                        required
                    >

                </div>

                {{-- Shift Start --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Shift Start
                    </label>

                    <input
                        type="time"
                        name="ShiftStart"
                        class="form-control"
                        value="{{ old('ShiftStart', $workSchedule->ShiftStart) }}"
                        required
                    >

                </div>

                {{-- Shift End --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Shift End
                    </label>

                    <input
                        type="time"
                        name="ShiftEnd"
                        class="form-control"
                        value="{{ old('ShiftEnd', $workSchedule->ShiftEnd) }}"
                        required
                    >

                </div>

                {{-- Actual Check In --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Actual Check In
                    </label>

                    <input
                        type="datetime-local"
                        name="ActualCheckIn"
                        class="form-control"
                        value="{{ old(
                            'ActualCheckIn',
                            $workSchedule->ActualCheckIn?->format('Y-m-d\TH:i')
                        ) }}"
                    >

                </div>

                {{-- Actual Check Out --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Actual Check Out
                    </label>

                    <input
                        type="datetime-local"
                        name="ActualCheckOut"
                        class="form-control"
                        value="{{ old(
                            'ActualCheckOut',
                            $workSchedule->ActualCheckOut?->format('Y-m-d\TH:i')
                        ) }}"
                    >

                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Status
                    </label>

                    <select
                        name="Status"
                        class="form-select"
                        required
                    >

                        <option
                            value="Scheduled"
                            {{ old('Status', $workSchedule->Status) == 'Scheduled' ? 'selected' : '' }}
                        >
                            Scheduled
                        </option>

                        <option
                            value="OnLeave"
                            {{ old('Status', $workSchedule->Status) == 'OnLeave' ? 'selected' : '' }}
                        >
                            On Leave
                        </option>

                        <option
                            value="Completed"
                            {{ old('Status', $workSchedule->Status) == 'Completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                    </select>

                </div>

                {{-- Worked Hours --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Worked Hours
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $workSchedule->WorkedHours !== null ? number_format($workSchedule->WorkedHours, 2) . ' hours' : '-' }}"
                        disabled
                    >

                    <small class="text-muted">
                        Worked hours are calculated automatically.
                    </small>

                </div>

            </div>

            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Work Schedule
                </button>

                <a
                    href="{{ route('work-schedules.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection