@extends('layouts.receptionist.app')

@section('title', 'New appointment')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Create walk-in appointment</h1>
            <p class="text-muted mb-0">Use this form for walk-in customers or phone bookings.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.appointments.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Existing customer (optional)</label>
                    <select name="customer_id" class="form-control" id="customerSelect">
                        <option value="">-- New customer --</option>
                        @foreach ($customers as $customer)
                        <option value="{{ $customer->CustomerID }}" data-allergies="{{ $customer->Allergies }}"
                            @selected(old('customer_id', request('customer_id'))==$customer->CustomerID)>
                            {{ $customer->FullName }} - {{ $customer->Phone }}
                        </option>
                        @endforeach
                    </select>
                    <div id="allergyWarning" class="alert alert-warning mt-2 py-2 px-3 mb-0 d-none">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Allergy alert:</strong> <span id="allergyWarningText"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Assign staff (optional)</label>
                    <select name="staff_id" id="staffSelect" class="form-control">
                        <option value="">-- Not assigned yet --</option>
                        @foreach ($staffList as $staff)
                        <option value="{{ $staff->UserID }}" @selected(old('staff_id')==$staff->UserID)>
                            {{ $staff->Username }}{{ $staff->Position ? ' - ' . $staff->Position : '' }}{{ !$staff->IsActive ? ' (Inactive)' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <div class="form-text">Staff who are already booked for the chosen date/time are marked "(Busy)" and
                        cannot be selected.</div>
                </div>

                <div class="col-md-6 new-customer-field">
                    <label class="form-label">New customer name</label>
                    <input type="text" name="fullname" id="fullnameInput" class="form-control"
                        value="{{ old('fullname') }}">
                </div>
                <div class="col-md-6 new-customer-field">
                    <label class="form-label">Phone number</label>
                    <input type="text" name="phone" id="phoneInput" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Service <span class="text-danger">*</span></label>
                    <select name="service_id" id="serviceSelect" class="form-control" required>
                        <option value="">-- Select service --</option>
                        @foreach ($services as $service)
                        <option value="{{ $service->ServiceID }}" @selected(old('service_id')==$service->ServiceID)>
                            {{ $service->Category }} - {{ $service->ServiceName }}
                            ({{ number_format($service->Price, 0, ',', '.') }}đ, {{ $service->DurationMinutes }}
                            minutes)
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Appointment date <span class="text-danger">*</span></label>
                    <input type="date" name="appointment_date" id="appointmentDateInput" class="form-control"
                        value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Appointment time <span class="text-danger">*</span></label>
                    <input type="time" name="appointment_time" id="appointmentTimeInput" class="form-control"
                        value="{{ old('appointment_time') }}" required>
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
                            <span class="text-muted small">Pick a service and date to see open slots</span>
                        </div>
                        <div id="freeSlotsBody"></div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create appointment
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customerSelect');
    const allergyBox = document.getElementById('allergyWarning');
    const allergyText = document.getElementById('allergyWarningText');
    const newCustomerFields = document.querySelectorAll('.new-customer-field');
    const fullnameInput = document.getElementById('fullnameInput');
    const phoneInput = document.getElementById('phoneInput');

    function updateAllergyWarning() {
        const option = customerSelect.options[customerSelect.selectedIndex];
        const allergies = option ? option.getAttribute('data-allergies') : '';

        if (allergies) {
            allergyText.textContent = allergies;
            allergyBox.classList.remove('d-none');
        } else {
            allergyBox.classList.add('d-none');
        }
    }

    function updateNewCustomerFields() {
        const hasExistingCustomer = customerSelect.value !== '';

        newCustomerFields.forEach(function(field) {
            field.classList.toggle('d-none', hasExistingCustomer);
        });

        // Clear and disable so their (empty) values are never submitted
        // or mistaken for required input when an existing customer is picked.
        fullnameInput.disabled = hasExistingCustomer;
        phoneInput.disabled = hasExistingCustomer;
        if (hasExistingCustomer) {
            fullnameInput.value = '';
            phoneInput.value = '';
        }
    }

    if (customerSelect) {
        customerSelect.addEventListener('change', updateAllergyWarning);
        customerSelect.addEventListener('change', updateNewCustomerFields);
        updateAllergyWarning();
        updateNewCustomerFields();
    }

    // --- Live availability check: disable staff who are already busy
    // for the chosen date/time, and warn if the selected customer
    // already has a conflicting appointment. The server still enforces
    // both checks on submit, this is just live feedback. ---
    const dateInput = document.getElementById('appointmentDateInput');
    const timeInput = document.getElementById('appointmentTimeInput');
    const serviceSelect = document.getElementById('serviceSelect');
    const staffSelect = document.getElementById('staffSelect');
    const conflictBox = document.getElementById('customerConflictWarning');
    const conflictText = document.getElementById('customerConflictText');

    Array.from(staffSelect.options).forEach(function(opt) {
        opt.dataset.baseText = opt.textContent;
    });

    async function checkAvailability() {
        // Only the date is required. As soon as it's picked we can already
        // gray out staff with no work schedule that day / on leave; the
        // busy-staff check is added in once time + service are also filled.
        if (!dateInput.value) {
            return;
        }

        const params = new URLSearchParams({
            appointment_date: dateInput.value,
        });

        if (timeInput.value) {
            params.append('appointment_time', timeInput.value);
        }

        if (serviceSelect.value) {
            params.append('service_id', serviceSelect.value);
        }

        if (customerSelect && customerSelect.value) {
            params.append('customer_id', customerSelect.value);
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

    [dateInput, timeInput, serviceSelect, customerSelect].forEach(function(el) {
        if (el) {
            el.addEventListener('change', checkAvailability);
        }
    });

    // Run once on load too - the date field is pre-filled with today's
    // date by default, which doesn't fire a 'change' event on its own.
    checkAvailability();

    // --- Free slots panel: given service + date, show open start times
    // per staff member so the receptionist can pick one instead of
    // guessing a time first. ---
    const freeSlotsPanel = document.getElementById('freeSlotsPanel');
    const freeSlotsBody = document.getElementById('freeSlotsBody');

    async function loadFreeSlots() {
        if (!dateInput.value || !serviceSelect.value) {
            freeSlotsPanel.classList.add('d-none');
            return;
        }

        const params = new URLSearchParams({
            appointment_date: dateInput.value,
            service_id: serviceSelect.value,
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
                    .no_schedule ? ' — no schedule this day' : ''));
                row.appendChild(label);

                if (staff.on_leave || staff.no_schedule || staff.slots.length === 0) {
                    const empty = document.createElement('span');
                    empty.className = 'text-muted small';
                    empty.textContent = staff.on_leave ?
                        'Not working this day.' :
                        (staff.no_schedule ? 'No work schedule set for this day.' :
                            'No open slots left.');
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

    [dateInput, serviceSelect].forEach(function(el) {
        if (el) {
            el.addEventListener('change', loadFreeSlots);
        }
    });
});
</script>
@endsection