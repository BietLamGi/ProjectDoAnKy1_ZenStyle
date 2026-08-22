@extends('layouts.receptionist.app')

@section('title', 'Work Schedule')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Staff Work Schedule</h1>
            <p class="text-muted mb-0">View everyone's shifts and check-in/check-out.</p>
        </div>
    </div>

    <div class="panel">
        @if ($schedules->isEmpty())
        <div class="blank-panel blank-state text-center py-5 text-muted">
            No work schedules yet.
        </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Work Date</th>
                        <th>Shift</th>
                        <th>Actual In / Out</th>
                        <th>Hours Worked</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                    <tr>
                        <td class="fw-semibold">{{ $schedule->user->Username ?? 'N/A' }}</td>
                        <td>{{ $schedule->WorkDate?->format('d/m/Y') }}</td>
                        <td>
                            {{ substr((string) $schedule->ShiftStart, 0, 5) }}
                            -
                            {{ substr((string) $schedule->ShiftEnd, 0, 5) }}
                        </td>
                        <td>
                            {{ $schedule->ActualCheckIn?->format('H:i d/m') ?? '—' }}
                            /
                            {{ $schedule->ActualCheckOut?->format('H:i d/m') ?? '—' }}
                        </td>
                        <td>{{ $schedule->WorkedHours !== null ? number_format($schedule->WorkedHours, 2) . ' h' : '—' }}
                        </td>
                        <td>
                            <span
                                class="badge text-bg-{{ $schedule->Status === 'Completed' ? 'success' : ($schedule->Status === 'OnLeave' ? 'warning' : 'primary') }}">
                                {{ $schedule->Status }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if (!$schedule->WorkDate->isToday())
                            <span class="text-muted small">
                                @if ($schedule->WorkDate->isFuture())
                                Upcoming ({{ $schedule->WorkDate->format('d/m/Y') }})
                                @else
                                {{ $schedule->ActualCheckIn ? 'Done' : 'Not checked in' }}
                                @endif
                            </span>
                            @elseif (!$schedule->ActualCheckIn)
                            <form action="{{ route('receptionist.work-schedules.check-in', $schedule) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Check-in</button>
                            </form>
                            @elseif (!$schedule->ActualCheckOut)
                            <form action="{{ route('receptionist.work-schedules.check-out', $schedule) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Check-out</button>
                            </form>
                            @else
                            <span class="text-muted small">Done</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $schedules->links() }}
        </div>
        @endif
    </div>

</div>
@endsection