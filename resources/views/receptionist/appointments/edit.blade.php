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
                @if ($appointment->staff)
                &middot; Staff: {{ $appointment->staff->Username }}
                @endif
            </p>
            @if ($appointment->customer && $appointment->customer->Allergies)
            <div class="alert alert-warning mt-2 py-2 px-3 mb-0 d-inline-block">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Allergy alert:</strong> {{ $appointment->customer->Allergies }}
            </div>
            @endif
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
                            <input type="date" name="AppointmentDate" id="appointmentDateInput" class="form-control"
                                value="{{ old('AppointmentDate', optional($appointment->AppointmentDate)->format('Y-m-d')) }}"" required>
                        </div>
                        <div class=" col-md-6">
                            <label class="form-label">Start time</label>
                            <input type="time" name="StartTime" id="appointmentTimeInput" class="form-control"
                                value="{{ old('StartTime', \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i')) }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assigned staff</label>
                            <select name="StaffID" id="staffSelect" class="form-control">
                                <option value="">-- Not assigned yet --</option>
                                @foreach ($staffList as $staff)
                                <option value="{{ $staff->UserID }}" @selected(old('StaffID', $appointment->StaffID) ==
                                    $staff->UserID)>
                                    {{ $staff->Username }}{{ $staff->Position ? ' - ' . $staff->Position : '' }}{{ !$staff->IsActive ? ' (Inactive)' : '' }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">Staff who are already booked for this date/time are marked "(Busy)".
                            </div>
                        </div>
                        <div class="col-12">
                            <div id="customerConflictWarning" class="alert alert-danger py-2 px-3 mb-0 d-none">
                                <i class="bi bi-exclamation-triangle"></i>
                                <span id="customerConflictText"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div id="freeSlotsPanel" class="border rounded p-3 d-none">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Available time slots</strong>
                                </div>
                                <div id="freeSlotsBody"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            @php
                            $allowedNext =
                            \App\Http\Controllers\Receptionist\AppointmentController::ALLOWED_TRANSITIONS[$appointment->Status]
                            ?? [];
                            @endphp
                            <select name="Status" class="form-control">
                                <option value="{{ $appointment->Status }}" selected>{{ $appointment->Status }} (current)
                                </option>
                                @foreach ($allowedNext as $s)
                                <option value="{{ $s }}" @selected(old('Status')===$s)>{{ $s }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">"Completed" is set automatically once an invoice is created for this
                                appointment. Moving to "CheckedIn" requires a staff member to be assigned and the
                                appointment date to have arrived.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="Notes" class="form-control"
                                rows="3">{{ old('Notes', $appointment->Notes) }}</textarea>
                        </div>
                    </div>

                    <div class="heading-actions mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update
                        </button>
                        @if ($appointment->invoice)
                        <a href="{{ route('receptionist.invoices.show', $appointment->invoice) }}"
                            class="btn btn-sm btn-light" title="View invoice">
                            <i class="bi bi-receipt"></i>
                        </a>
                        @elseif ($appointment->Status !== 'Cancelled')
                        <a href="{{ route('receptionist.invoices.create', ['appointment_id' => $appointment->AppointmentID]) }}"
                            class="btn btn-sm btn-light" title="Create invoice">
                            <i class="bi bi-receipt"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <h5 class="mb-0">Booked services</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Qty</th>
                                <th>Unit price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointment->services as $line)
                            <tr>
                                <td>{{ $line->service->ServiceName ?? '—' }}</td>
                                <td>{{ $line->Quantity }}</td>
                                <td>{{ number_format($line->UnitPrice, 0, ',', '.') }}đ</td>
                                <td class="text-end">
                                    <form
                                        action="{{ route('receptionist.appointments.services.destroy', [$appointment, $line]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Remove this service from the appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted">No service added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('receptionist.appointments.services.store', $appointment) }}" method="POST"
                    class="d-flex gap-2 mt-2">
                    @csrf
                    <select name="ServiceID" class="form-control" required>
                        <option value="">-- Add service --</option>
                        @foreach ($services as $service)
                        <option value="{{ $service->ServiceID }}">{{ $service->ServiceName }}
                            ({{ number_format($service->Price, 0, ',', '.') }}đ)</option>
                        @endforeach
                    </select>
                    <input type="number" name="Quantity" class="form-control" style="max-width: 90px;" value="1" min="1"
                        required>
                    <button type="submit" class="btn btn-light" title="Add">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </form>

                <form action="{{ route('receptionist.appointments.destroy', $appointment) }}" method="POST" class="mt-2"
                    onsubmit="return confirm('Cancel this appointment?');">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointmentDateInput');
    const timeInput = document.getElementById('appointmentTimeInput');
    const staffSelect = document.getElementById('staffSelect');
    const conflictBox = document.getElementById('customerConflictWarning');
    const conflictText = document.getElementById('customerConflictText');
    const serviceId = {{ $appointment->services->first()->ServiceID ?? 'null' }};
    const customerId = {{ $appointment->CustomerID ?? 'null' }};
    const excludeId = {{ $appointment->AppointmentID }};

    Array.from(staffSelect.options).forEach(function(opt) {
        opt.dataset.baseText = opt.textContent;
    });

    async function checkAvailability() {
        // Only the date is required. As soon as it's picked we can already
        // gray out staff with no work schedule that day / on leave; the
        // busy-staff check needs time + service too (service comes from
        // the appointment's booked service, if any).
        if (!dateInput.value) {
            return;
        }

        const params = new URLSearchParams({
            appointment_date: dateInput.value,
            exclude_id: excludeId,
        });

        if (timeInput.value && serviceId) {
            params.append('appointment_time', timeInput.value);
            params.append('service_id', serviceId);
        }

        if (customerId) {
            params.append('customer_id', customerId);
        }

        try {
            const response = await fetch('{{ route("receptionist.appointments.availability") }}?' + params
                .toString(), {
                    headers: {
                        'Accept': 'application/json'
                    },
                });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            const busyIds = (data.busy_staff_ids || []).map(String);
            const leaveIds = (data.leave_staff_ids || []).map(String);
            const noScheduleIds = (data.no_schedule_staff_ids || []).map(String);

            Array.from(staffSelect.options).forEach(function(opt) {
                if (!opt.value) {
                    return;
                }
                const isBusy = busyIds.includes(opt.value);
                const isOnLeave = leaveIds.includes(opt.value);
                const hasNoSchedule = noScheduleIds.includes(opt.value);

                opt.disabled = isBusy || isOnLeave || hasNoSchedule;

                if (isOnLeave) {
                    opt.textContent = opt.dataset.baseText + ' (On leave)';
                } else if (isBusy) {
                    opt.textContent = opt.dataset.baseText + ' (Busy)';
                } else if (hasNoSchedule) {
                    opt.textContent = opt.dataset.baseText + ' (No schedule this day)';
                } else {
                    opt.textContent = opt.dataset.baseText;
                }
            });

            if (staffSelect.selectedOptions[0] && staffSelect.selectedOptions[0].disabled) {
                staffSelect.value = '';
            }

            if (data.customer_conflict) {
                conflictText.textContent = data.customer_conflict;
                conflictBox.classList.remove('d-none');
            } else {
                conflictBox.classList.add('d-none');
            }
        } catch (e) {
            // Network hiccup: server-side validation on submit still protects the data.
        }
    }

    [dateInput, timeInput].forEach(function(el) {
        el.addEventListener('change', checkAvailability);
    });

    // Run once on load too - the date/time fields are pre-filled from the
    // existing appointment, which doesn't fire a 'change' event on its own.
    checkAvailability();

    // --- Free slots panel, same idea as the create form: given the
    // appointment's (first) service + the chosen date, show open start
    // times per staff so the receptionist can pick one instead of
    // guessing a time first. ---
    const freeSlotsPanel = document.getElementById('freeSlotsPanel');
    const freeSlotsBody = document.getElementById('freeSlotsBody');

    async function loadFreeSlots() {
        if (!dateInput.value || !serviceId) {
            freeSlotsPanel.classList.add('d-none');
            return;
        }

        const params = new URLSearchParams({
            appointment_date: dateInput.value,
            service_id: serviceId,
            exclude_id: excludeId,
        });

        try {
            const response = await fetch('{{ route("receptionist.appointments.slots") }}?' + params
                .toString(), {
                    headers: {
                        'Accept': 'application/json'
                    },
                });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            freeSlotsBody.innerHTML = '';

            data.staff.forEach(function(staff) {
                const row = document.createElement('div');
                row.className = 'mb-2';

                const label = document.createElement('div');
                label.className = 'small text-muted mb-1';
                label.textContent = staff.name + (staff.on_leave ? ' — on leave' : (staff
                    .used_default_hours ? ' — default hours' : ''));
                row.appendChild(label);

                if (staff.on_leave || staff.slots.length === 0) {
                    const empty = document.createElement('span');
                    empty.className = 'text-muted small';
                    empty.textContent = staff.on_leave ? 'Not working this day.' :
                        'No open slots left.';
                    row.appendChild(empty);
                } else {
                    staff.slots.forEach(function(slot) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-sm btn-outline-primary me-1 mb-1';
                        btn.textContent = slot;
                        btn.addEventListener('click', function() {
                            timeInput.value = slot;
                            staffSelect.value = staff.staff_id;
                            checkAvailability();
                        });
                        row.appendChild(btn);
                    });
                }

                freeSlotsBody.appendChild(row);
            });

            freeSlotsPanel.classList.remove('d-none');
        } catch (e) {
            // Network hiccup: the receptionist can still type a time manually.
        }
    }

    dateInput.addEventListener('change', loadFreeSlots);
});
</script>
@endsection