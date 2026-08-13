@extends('layouts.receptionist.app')

@section('title', 'Appointments')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Appointments</h1>
            <p class="text-muted mb-0">Confirm, check in customers, and complete appointments.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New appointment
            </a>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <form class="d-flex flex-wrap gap-2" method="GET">
                <input type="date" name="date" value="{{ $date }}" class="form-control" style="max-width: 170px;">
                <select name="status" class="form-control" style="max-width: 170px;">
                    <option value="">All statuses</option>
                    @foreach (\App\Http\Controllers\Receptionist\AppointmentController::STATUSES as $s)
                        <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <input type="search" name="q" value="{{ $keyword }}" class="form-control table-search" placeholder="Customer name or phone...">
                <button class="btn btn-light" type="submit"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-outline-secondary">Clear filter</a>
            </form>
        </div>

        @if ($appointments->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                No matching appointments.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i') }}</div>
                                    <div class="text-muted small">{{ $appointment->AppointmentDate }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('receptionist.customers.show', $appointment->CustomerID) }}" class="fw-semibold text-decoration-none">
                                        {{ $appointment->customer->FullName ?? 'N/A' }}
                                    </a>
                                    <div class="text-muted small">{{ $appointment->customer->Phone ?? '' }}</div>
                                </td>
                                <td>{{ $appointment->services->pluck('service.ServiceName')->filter()->join(', ') ?: '—' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $appointment->Status === 'Completed' ? 'success' : ($appointment->Status === 'Cancelled' ? 'danger' : ($appointment->Status === 'CheckedIn' ? 'info' : 'warning')) }}">
                                        {{ $appointment->Status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Trạng thái
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach (\App\Http\Controllers\Receptionist\AppointmentController::STATUSES as $s)
                                                <li>
                                                    <form action="{{ route('receptionist.appointments.status', $appointment) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $s }}">
                                                        <button type="submit" class="dropdown-item {{ $appointment->Status === $s ? 'fw-bold' : '' }}">{{ $s }}</button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <a href="{{ route('receptionist.invoices.create', ['appointment_id' => $appointment->AppointmentID]) }}" class="btn btn-sm btn-light" title="Create invoice">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
