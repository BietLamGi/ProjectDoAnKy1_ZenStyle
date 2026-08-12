@extends('layouts.receptionist.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Create Invoice</h1>
            <p class="text-muted mb-0">Choose an appointment and generate an invoice for the customer.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <div class="mb-3">
            <label class="form-label">Select appointment</label>
            <select class="form-control" onchange="if(this.value){ window.location='{{ route('receptionist.invoices.create') }}?appointment_id='+this.value; }">
                <option value="">-- Select appointment to calculate automatically --</option>
                @foreach ($appointments as $appointment)
                    <option value="{{ $appointment->AppointmentID }}" @selected($selectedAppointment && $selectedAppointment->AppointmentID == $appointment->AppointmentID)>
                        #{{ $appointment->AppointmentID }} - {{ $appointment->customer->FullName ?? 'N/A' }} ({{ $appointment->AppointmentDate }})
                    </option>
                @endforeach
            </select>
        </div>

        @if ($selectedAppointment)
            <div class="blank-panel mb-3">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Service</th><th>Qty</th><th>Unit price</th><th>Total</th></tr></thead>
                        <tbody>
                            @forelse ($selectedAppointment->services as $line)
                                <tr>
                                    <td>{{ $line->service->ServiceName ?? '—' }}</td>
                                    <td>{{ $line->Quantity }}</td>
                                    <td>{{ number_format($line->UnitPrice, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($line->Quantity * $line->UnitPrice, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">This appointment has no services yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('receptionist.invoices.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Appointment <span class="text-danger">*</span></label>
                    <select name="AppointmentID" class="form-control" required>
                        <option value="">-- Select appointment --</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->AppointmentID }}"
                                @selected(old('AppointmentID', $selectedAppointment->AppointmentID ?? null) == $appointment->AppointmentID)>
                                #{{ $appointment->AppointmentID }} - {{ $appointment->customer->FullName ?? 'N/A' }} ({{ $appointment->AppointmentDate }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment method</label>
                    <select name="PaymentMethod" class="form-control">
                        <option value="">-- Select payment method --</option>
                        <option value="Cash" @selected(old('PaymentMethod') == 'Cash')>Cash</option>
                        <option value="Bank Transfer" @selected(old('PaymentMethod') == 'Bank Transfer')>Bank Transfer</option>
                        <option value="Credit Card" @selected(old('PaymentMethod') == 'Credit Card')>Credit Card</option>
                        <option value="E-Wallet" @selected(old('PaymentMethod') == 'E-Wallet')>E-Wallet</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal <span class="text-danger">*</span></label>
                    <input type="number" id="totalAmount" name="TotalAmount" class="form-control" step="0.01" min="0" value="{{ old('TotalAmount', $totalAmount) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount <span class="text-danger">*</span></label>
                    <input type="number" id="discountAmount" name="DiscountAmount" class="form-control" step="0.01" min="0" value="{{ old('DiscountAmount', 0) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Final amount <span class="text-danger">*</span></label>
                    <input type="number" id="finalAmount" name="FinalAmount" class="form-control" step="0.01" min="0" value="{{ old('FinalAmount', $totalAmount) }}" required readonly>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create invoice
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalInput = document.getElementById('totalAmount');
        const discountInput = document.getElementById('discountAmount');
        const finalInput = document.getElementById('finalAmount');

        function updateFinalAmount() {
            const total = parseFloat(totalInput.value || 0);
            const discount = parseFloat(discountInput.value || 0);
            finalInput.value = Math.max(0, total - discount).toFixed(2);
        }

        if (totalInput && discountInput && finalInput) {
            totalInput.addEventListener('input', updateFinalAmount);
            discountInput.addEventListener('input', updateFinalAmount);
            updateFinalAmount();
        }
    });
</script>
@endsection
