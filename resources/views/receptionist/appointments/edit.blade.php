@extends('layouts.receptionist.app')

@section('title', 'Edit appointment')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Appointment #{{ $appointment->AppointmentID }}</h1>
            <p class="text-muted mb-0">
                {{ $appointment->customer->FullName ?? 'N/A' }} &middot; {{ $appointment->customer->Phone ?? '' }}
            </p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel">
                <form method="POST" action="{{ route('receptionist.appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Appointment date</label>
                            <input type="date" name="AppointmentDate" class="form-control" value="{{ old('AppointmentDate', $appointment->AppointmentDate) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start time</label>
                            <input type="time" name="StartTime" class="form-control" value="{{ old('StartTime', \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="Status" class="form-control">
                                @foreach ($statuses as $s)
                                    <option value="{{ $s }}" @selected(old('Status', $appointment->Status) === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="Notes" class="form-control" rows="3">{{ old('Notes', $appointment->Notes) }}</textarea>
                        </div>
                    </div>

                    <div class="heading-actions mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update
                        </button>
                        @if ($appointment->invoice)
                            <a href="{{ route('receptionist.invoices.show', $appointment->invoice) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-receipt"></i> View invoice
                            </a>
                        @else
                            <a href="{{ route('receptionist.invoices.create', ['appointment_id' => $appointment->AppointmentID]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-receipt"></i> Create invoice
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Booked services</h5></div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Service</th><th>Qty</th><th>Unit price</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($appointment->services as $line)
                                <tr>
                                    <td>{{ $line->service->ServiceName ?? '—' }}</td>
                                    <td>{{ $line->Quantity }}</td>
                                    <td>{{ number_format($line->UnitPrice, 0, ',', '.') }}đ</td>
                                    <td class="text-end">
                                        <form action="{{ route('receptionist.appointments.services.destroy', [$appointment, $line]) }}" method="POST" onsubmit="return confirm('Remove this service from the appointment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">No service added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('receptionist.appointments.services.store', $appointment) }}" method="POST" class="d-flex gap-2 mt-2">
                    @csrf
                    <select name="ServiceID" class="form-control" required>
                        <option value="">-- Add service --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->ServiceID }}">{{ $service->ServiceName }} ({{ number_format($service->Price, 0, ',', '.') }}đ)</option>
                        @endforeach
                    </select>
                    <input type="number" name="Quantity" class="form-control" style="max-width: 90px;" value="1" min="1" required>
                    <button type="submit" class="btn btn-light" title="Add">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </form>

                <form action="{{ route('receptionist.appointments.destroy', $appointment) }}" method="POST" class="mt-2" onsubmit="return confirm('Cancel this appointment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i> Cancel appointment
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
