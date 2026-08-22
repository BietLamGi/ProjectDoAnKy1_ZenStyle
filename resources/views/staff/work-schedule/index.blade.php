@extends('layouts.staff.staff')

@section('title', 'Work Schedule')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="mb-1">
            Work Schedule
        </h2>

        <p class="text-muted mb-0">
            View your assigned shifts and customer appointments.
        </p>

    </div>


    {{-- ================================================= --}}
    {{-- MY WORK SHIFTS --}}
    {{-- ================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-clock me-2"></i>

                My Work Shifts

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Date</th>
                            <th>Shift</th>
                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($schedules as $schedule)

                        <tr>

                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $schedule->WorkDate
                                )->format('d/m/Y') }}

                            </td>


                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $schedule->ShiftStart
                                )->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $schedule->ShiftEnd
                                )->format('H:i') }}

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

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3"
                                class="text-center text-muted py-5">

                                <i class="bi bi-calendar3 fs-3 d-block mb-2"></i>

                                No work schedule found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- ASSIGNED APPOINTMENTS --}}
    {{-- ================================================= --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-calendar-check me-2"></i>

                Assigned Appointments

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Date</th>
                            <th>Time</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Notes</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($appointments as $appointment)

                        <tr>

                            {{-- DATE --}}
                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $appointment->AppointmentDate
                                )->format('d/m/Y') }}

                            </td>


                            {{-- TIME --}}
                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $appointment->StartTime
                                )->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $appointment->EndTime
                                )->format('H:i') }}

                            </td>


                            {{-- CUSTOMER --}}
                            <td>

                                @if($appointment->customer)

                                    <strong>
                                        {{ $appointment->customer->FullName }}
                                    </strong>

                                @else

                                    <span class="text-muted">
                                        Unknown
                                    </span>

                                @endif

                            </td>


                            {{-- SERVICE --}}
                            <td>

                                @forelse(
                                    $appointment->services
                                    as $appointmentService
                                )

                                    @if($appointmentService->service)

                                        <span class="badge bg-light text-dark mb-1">

                                            {{ $appointmentService->service->ServiceName }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Unknown Service
                                        </span>

                                    @endif

                                @empty

                                    <span class="text-muted">
                                        No service
                                    </span>

                                @endforelse

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($appointment->Status === 'Booked')

                                    <span class="badge bg-primary">
                                        Booked
                                    </span>

                                @elseif($appointment->Status === 'Confirmed')

                                    <span class="badge bg-info text-dark">
                                        Confirmed
                                    </span>

                                @elseif($appointment->Status === 'Completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif($appointment->Status === 'Cancelled')

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $appointment->Status }}
                                    </span>

                                @endif

                            </td>


                            {{-- NOTES --}}
                            <td>

                                @if($appointment->Notes)

                                    {{ $appointment->Notes }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-5">

                                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                                No appointments assigned to you.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection