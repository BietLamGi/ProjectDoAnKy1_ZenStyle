@extends('layouts.receptionist.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Invoice #{{ $invoice->InvoiceID }}</h1>
            <p class="text-muted mb-0">{{ $invoice->appointment->customer->FullName ?? '' }}</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Appointment <span class="text-danger">*</span></label>
                    <select name="AppointmentID" class="form-control" required>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->AppointmentID }}" @selected(old('AppointmentID', $invoice->AppointmentID) == $appointment->AppointmentID)>
                                #{{ $appointment->AppointmentID }} - {{ $appointment->customer->FullName ?? 'N/A' }} ({{ $appointment->AppointmentDate }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Invoice date <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="InvoiceDate" class="form-control"
                        value="{{ old('InvoiceDate', $invoice->InvoiceDate?->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment method</label>
                    <select name="PaymentMethod" class="form-control">
                        <option value="">-- Select payment method --</option>
                        <option value="Cash" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'Cash')>Cash</option>
                        <option value="Bank Transfer" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'Bank Transfer')>Bank Transfer</option>
                        <option value="Credit Card" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'Credit Card')>Credit Card</option>
                        <option value="E-Wallet" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'E-Wallet')>E-Wallet</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal <span class="text-danger">*</span></label>
                    <input type="number" id="totalAmount" name="TotalAmount" class="form-control" step="0.01" min="0" value="{{ old('TotalAmount', $invoice->TotalAmount) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount <span class="text-danger">*</span></label>
                    <input type="number" id="discountAmount" name="DiscountAmount" class="form-control" step="0.01" min="0" value="{{ old('DiscountAmount', $invoice->DiscountAmount) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Final amount <span class="text-danger">*</span></label>
                    <input type="number" id="finalAmount" name="FinalAmount" class="form-control" step="0.01" min="0" value="{{ old('FinalAmount', $invoice->FinalAmount) }}" required readonly>
                </div>
            </div>

            @if ($invoice->appointment && $invoice->appointment->services->isNotEmpty())
                <div class="blank-panel mt-3">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead><tr><th>Service</th><th>Qty</th><th>Unit price</th></tr></thead>
                            <tbody>
                                @foreach ($invoice->appointment->services as $line)
                                    <tr>
                                        <td>{{ $line->service->ServiceName ?? '—' }}</td>
                                        <td>{{ $line->Quantity }}</td>
                                        <td>{{ number_format($line->UnitPrice, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update
                </button>
            </div>
        </form>

        <form action="{{ route('receptionist.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete this invoice?');" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete invoice
            </button>
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
