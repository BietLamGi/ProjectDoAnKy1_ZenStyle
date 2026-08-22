@extends('layouts.receptionist.app')

@section('title', 'Appointment details')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Appointment #{{ $appointment->AppointmentID }}</h1>
        </div>
        <div class="heading-actions">
            @php
            $isLocked = in_array($appointment->Status,
            \App\Http\Controllers\Receptionist\AppointmentController::LOCKED_STATUSES, true);
            @endphp
            @if (!$isLocked)
            <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            @if ($appointment->invoice)
            <a href="{{ route('receptionist.invoices.show', $appointment->invoice) }}"
                class="btn btn-outline-secondary">
                <i class="bi bi-receipt"></i> View invoice
            </a>
            @endif
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="panel">
        @if ($isLocked)
        <div class="alert alert-secondary py-2 px-3">
            <i class="bi bi-lock"></i>
            This appointment is <strong>{{ $appointment->Status }}</strong> - view only, no further changes allowed.
        </div>
        @endif
        @if ($appointment->customer && $appointment->customer->Allergies)
        <div class="alert alert-warning py-2 px-3">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Allergy alert:</strong> {{ $appointment->customer->Allergies }}
        </div>
        @endif
        <div class="info-list">
            <div><span>Customer</span><strong>{{ $appointment->customer->FullName ?? 'N/A' }}</strong></div>
            <div><span>Phone</span><strong>{{ $appointment->customer->Phone ?? '—' }}</strong></div>
            <div><span>Staff</span><strong>{{ $appointment->staff->Username ?? 'Not assigned yet' }}</strong></div>
            <div><span>Date &amp; time</span><strong>{{ $appointment->AppointmentDate?->format('d/m/Y') }}
                    {{ \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i') }}</strong></div>
            <div><span>Status</span><strong>{{ $appointment->Status }}</strong></div>
            <div>
                <span>Service</span><strong>{{ $appointment->services->pluck('service.ServiceName')->filter()->join(', ') ?: '—' }}</strong>
            </div>
            <div><span>Notes</span><strong>{{ $appointment->Notes ?: '—' }}</strong></div>
        </div>
    </div>

</div>
@endsection