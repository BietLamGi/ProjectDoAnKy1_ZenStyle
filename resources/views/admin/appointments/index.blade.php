@extends('layouts.admin.admin')

@section('content')

<div class="page-header">
    <div>
        <h1>Appointments</h1>
        <p>Manage salon appointments</p>
    </div>

    <a href="{{ route('appointments.create') }}" class="btn-primary">
        + Add Appointment
    </a>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">
        <strong>Appointments</strong>
        <span>Total: {{ $appointments->total() }}</span>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Staff</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>
                            {{ $appointment->AppointmentID }}
                        </td>

                        <td>
                            {{ $appointment->customer?->FullName ?? '-' }}
                        </td>

                        <td>
                            {{ $appointment->staff?->Username ?? '-' }}
                        </td>

                        <td>
                            {{ $appointment->AppointmentDate?->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $appointment->StartTime ? \Carbon\Carbon::parse($appointment->StartTime)->format('H:i') : '-' }}
                        </td>

                        <td>
                            {{ $appointment->EndTime ? \Carbon\Carbon::parse($appointment->EndTime)->format('H:i') : '-' }}
                        </td>

                        <td>
                            {{ $appointment->Status }}
                        </td>

                        <td>
                            {{ $appointment->Notes ?? '-' }}
                        </td>

                        <td>
                            <a href="{{ route('appointments.edit', $appointment->AppointmentID) }}">
                                Edit
                            </a>

                            <form
                                action="{{ route('appointments.destroy', $appointment->AppointmentID) }}"
                                method="POST"
                                style="display:inline"
                                onsubmit="return confirm('Are you sure you want to delete this appointment?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            No appointments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $appointments->links() }}

</div>

@endsection