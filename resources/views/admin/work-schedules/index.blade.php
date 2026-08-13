@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Work Schedule</h1>
        <p class="text-muted">Manage staff work schedules</p>
    </div>

    <a href="{{ route('work-schedules.create') }}" class="btn btn-primary">
        + Add Work Schedule
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
                Work Schedule List
            </h5>

            <span class="text-muted">
                Total: {{ $schedules->total() }}
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Staff</th>
                        <th>Work Date</th>
                        <th>Shift</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Worked Hours</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($schedules as $schedule)

                        <tr>

                            <td>
                                {{ $schedule->ScheduleID }}
                            </td>

                            <td>
                                <strong>
                                    {{ $schedule->user->Username ?? 'Unknown Staff' }}
                                </strong>
                            </td>

                            <td>
                                {{ $schedule->WorkDate?->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $schedule->ShiftStart }}
                                -
                                {{ $schedule->ShiftEnd }}
                            </td>

                            <td>
                                @if($schedule->ActualCheckIn)
                                    {{ $schedule->ActualCheckIn->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if($schedule->ActualCheckOut)
                                    {{ $schedule->ActualCheckOut->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if($schedule->WorkedHours !== null)
                                    {{ number_format($schedule->WorkedHours, 2) }} h
                                @else
                                    -
                                @endif
                            </td>

                            <td>

                                @if($schedule->Status === 'Scheduled')

                                    <span class="badge bg-primary">
                                        Scheduled
                                    </span>

                                @elseif($schedule->Status === 'OnLeave')

                                    <span class="badge bg-warning text-dark">
                                        On Leave
                                    </span>

                                @elseif($schedule->Status === 'Completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $schedule->Status }}
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route('work-schedules.edit', $schedule->ScheduleID) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    ✏️
                                </a>

                                <form
                                    action="{{ route('work-schedules.destroy', $schedule->ScheduleID) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this work schedule?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        🗑️
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="text-center py-4">
                                No work schedules found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $schedules->links() }}
        </div>

    </div>
</div>

@endsection