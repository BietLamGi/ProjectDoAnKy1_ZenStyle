@extends('layouts.receptionist.app')

@section('title', 'Edit work schedule')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Edit work schedule</h1>
            <p class="text-muted mb-0">{{ $workSchedule->user->Username ?? '' }} &middot; {{ $workSchedule->WorkDate?->format('d/m/Y') }}</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.work-schedules.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.work-schedules.update', $workSchedule) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Staff <span class="text-danger">*</span></label>
                    <select name="UserID" class="form-control" required>
                        <option value="">-- Select staff --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->UserID }}" @selected(old('UserID', $workSchedule->UserID) == $user->UserID)>
                                {{ $user->Username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Work date <span class="text-danger">*</span></label>
                    <input type="date" name="WorkDate" class="form-control" value="{{ old('WorkDate', $workSchedule->WorkDate?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Shift start time <span class="text-danger">*</span></label>
                    <input type="time" name="ShiftStart" class="form-control" value="{{ old('ShiftStart', $workSchedule->ShiftStart) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Shift end time <span class="text-danger">*</span></label>
                    <input type="time" name="ShiftEnd" class="form-control" value="{{ old('ShiftEnd', $workSchedule->ShiftEnd) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Actual check-in</label>
                    <input type="datetime-local" name="ActualCheckIn" class="form-control" value="{{ old('ActualCheckIn', $workSchedule->ActualCheckIn?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Actual check-out</label>
                    <input type="datetime-local" name="ActualCheckOut" class="form-control" value="{{ old('ActualCheckOut', $workSchedule->ActualCheckOut?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="Status" class="form-control" required>
                        <option value="Scheduled" @selected(old('Status', $workSchedule->Status) == 'Scheduled')>Scheduled</option>
                        <option value="OnLeave" @selected(old('Status', $workSchedule->Status) == 'OnLeave')>On Leave</option>
                        <option value="Completed" @selected(old('Status', $workSchedule->Status) == 'Completed')>Completed</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số giờ làm</label>
                    <input type="text" class="form-control" value="{{ $workSchedule->WorkedHours !== null ? number_format($workSchedule->WorkedHours, 2) . ' giờ' : '—' }}" disabled>
                    <small class="text-muted">Số giờ làm được hệ thống tự tính.</small>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update
                </button>
            </div>
        </form>

        <form action="{{ route('receptionist.work-schedules.destroy', $workSchedule) }}" method="POST" onsubmit="return confirm('Delete this work schedule?');" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete work schedule
            </button>
        </form>
    </div>

</div>
@endsection
