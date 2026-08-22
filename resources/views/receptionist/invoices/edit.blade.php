@extends('layouts.receptionist.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Invoice #{{ $invoice->InvoiceID }}</h1>
            <p class="text-muted mb-0">{{ $invoice->appointment?->customer?->FullName ?? '' }}</p>
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
                    <label class="form-label">Appointment</label>
                    <input type="text" class="form-control" disabled
                        value="#{{ $invoice->AppointmentID }} - {{ $invoice->appointment?->customer?->FullName ?? 'N/A' }} ({{ $invoice->appointment?->AppointmentDate }})">
                    <div class="form-text">This invoice stays tied to its original appointment - it can't be moved.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Invoice date</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $invoice->InvoiceDate?->format('d/m/Y H:i') }}">
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

                <div class="col-md-6">
                    <label class="form-label">Apply promotion (optional)</label>
                    <select id="promotionSelect" name="PromotionID" class="form-control">
                        <option value="">-- No promotion --</option>
                        @foreach ($activePromotions as $promotion)
                            <option value="{{ $promotion->PromotionID }}"
                                data-type="{{ $promotion->DiscountType }}"
                                data-value="{{ $promotion->DiscountValue }}"
                                @selected(old('PromotionID') == $promotion->PromotionID)>
                                {{ $promotion->Title }}
                                ({{ $promotion->DiscountType === 'Percent' ? $promotion->DiscountValue . '%' : number_format($promotion->DiscountValue, 0, ',', '.') . 'đ' }})
                                @if ($promotion->service) &middot; {{ $promotion->service->ServiceName }} @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Selecting a promotion recalculates the discount below. The subtotal, discount, and final amount are always recomputed on save from the booked services and this promotion - the preview fields aren't editable directly.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal</label>
                    <input type="number" id="totalAmount" class="form-control" step="0.01" min="0" value="{{ $totalAmount }}" disabled>
                    <div class="form-text">Automatically summed from the services below.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount</label>
                    <input type="number" id="discountAmount" class="form-control" step="0.01" min="0" value="{{ $invoice->DiscountAmount }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Final amount</label>
                    <input type="number" id="finalAmount" class="form-control" step="0.01" min="0" value="{{ $invoice->FinalAmount }}" disabled>
                </div>
            </div>

            @if ($invoice->appointment && $invoice->appointment->services->isNotEmpty())
                <div class="blank-panel mt-3">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0" id="servicesTable">
                            <thead><tr><th>Service</th><th>Qty</th><th>Unit price</th></tr></thead>
                            <tbody>
                                @foreach ($invoice->appointment->services as $line)
                                    <tr data-line-total="{{ $line->Quantity * $line->UnitPrice }}">
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
        // These three fields are preview-only (disabled, not submitted) -
        // the server always recomputes Subtotal/Discount/Final from the
        // booked services + selected promotion on save. This script only
        // keeps the preview in sync with the promotion dropdown.
        const totalInput = document.getElementById('totalAmount');
        const discountInput = document.getElementById('discountAmount');
        const finalInput = document.getElementById('finalAmount');
        const promotionSelect = document.getElementById('promotionSelect');

        function updateFinalAmount() {
            const total = parseFloat(totalInput.value || 0);
            const discount = parseFloat(discountInput.value || 0);
            finalInput.value = Math.max(0, total - discount).toFixed(2);
        }

        function applyPromotion() {
            const option = promotionSelect.options[promotionSelect.selectedIndex];
            const type = option ? option.getAttribute('data-type') : null;
            const value = option ? parseFloat(option.getAttribute('data-value')) : 0;
            const total = parseFloat(totalInput.value || 0);

            if (!type) {
                discountInput.value = '0.00';
                updateFinalAmount();
                return;
            }

            const discount = type === 'Percent' ? (total * value / 100) : value;
            discountInput.value = Math.max(0, Math.min(discount, total)).toFixed(2);
            updateFinalAmount();
        }

        function autoSumServices() {
            const rows = document.querySelectorAll('#servicesTable tbody tr[data-line-total]');
            if (!rows.length) {
                return;
            }

            let sum = 0;
            rows.forEach(function (row) {
                sum += parseFloat(row.getAttribute('data-line-total')) || 0;
            });

            totalInput.value = sum.toFixed(2);
            updateFinalAmount();
        }

        if (totalInput && discountInput && finalInput) {
            // Subtotal is always driven by the services table - recompute on load
            // so the preview can never go stale.
            autoSumServices();
        }

        if (promotionSelect) {
            promotionSelect.addEventListener('change', applyPromotion);
        }
    });
</script>
@endsection