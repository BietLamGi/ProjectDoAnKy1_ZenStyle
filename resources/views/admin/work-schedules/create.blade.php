@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold">Create Work Schedule</h1>
        <p class="text-muted">Create a new staff work schedule</p>
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
            action="{{ route('work-schedules.store') }}"
            method="POST"
        >

            @csrf

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
                                @selected(old('UserID') == $user->UserID)
                            >
                                {{ $user->Username }}
                            </option>

                        @endforeach

                    </select>

                    @error('UserID')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

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
                        value="{{ old('WorkDate') }}"
                        required
                    >

                    @error('WorkDate')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

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
                        value="{{ old('ShiftStart') }}"
                        required
                    >

                    @error('ShiftStart')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

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
                        value="{{ old('ShiftEnd') }}"
                        required
                    >

                    @error('ShiftEnd')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

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
                            @selected(old('Status', 'Scheduled') === 'Scheduled')
                        >
                            Scheduled
                        </option>

                        <option
                            value="OnLeave"
                            @selected(old('Status') === 'OnLeave')
                        >
                            On Leave
                        </option>

                        <option
                            value="Completed"
                            @selected(old('Status') === 'Completed')
                        >
                            Completed
                        </option>

                    </select>

                    @error('Status')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Information --}}
            <div class="alert alert-info mt-3">

                <i class="bi bi-info-circle"></i>

                <strong>Note:</strong>
                This form only creates the staff's planned work schedule.
                Actual Check In, Check Out and Worked Hours will be recorded
                automatically when the staff works.

            </div>


            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Work Schedule
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