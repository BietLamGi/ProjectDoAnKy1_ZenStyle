@extends('layouts.receptionist.app')

@section('title', 'Add work schedule')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Add work schedule</h1>
            <p class="text-muted mb-0">Schedule work shifts for staff.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.work-schedules.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.work-schedules.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Staff <span class="text-danger">*</span></label>
                    <select name="UserID" class="form-control" required>
                        <option value="">-- Select staff --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->UserID }}" @selected(old('UserID') == $user->UserID)>
                                {{ $user->Username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Work date <span class="text-danger">*</span></label>
                    <input type="date" name="WorkDate" class="form-control" value="{{ old('WorkDate') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Shift start time <span class="text-danger">*</span></label>
                    <input type="time" name="ShiftStart" class="form-control" value="{{ old('ShiftStart') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Shift end time <span class="text-danger">*</span></label>
                    <input type="time" name="ShiftEnd" class="form-control" value="{{ old('ShiftEnd') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Actual check-in</label>
                    <input type="datetime-local" name="ActualCheckIn" class="form-control" value="{{ old('ActualCheckIn') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Actual check-out</label>
                    <input type="datetime-local" name="ActualCheckOut" class="form-control" value="{{ old('ActualCheckOut') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="Status" class="form-control" required>
                        <option value="Scheduled" @selected(old('Status', 'Scheduled') == 'Scheduled')>Scheduled</option>
                        <option value="OnLeave" @selected(old('Status') == 'OnLeave')>On Leave</option>
                        <option value="Completed" @selected(old('Status') == 'Completed')>Completed</option>
                    </select>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save work schedule
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
