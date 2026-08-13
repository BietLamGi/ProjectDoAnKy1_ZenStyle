@extends('layouts.receptionist.app')

@section('title', 'Invoice details')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Invoice #{{ $invoice->InvoiceID }}</h1>
            <p class="text-muted mb-0">{{ $invoice->InvoiceDate?->format('d/m/Y H:i') }}</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.invoices.edit', $invoice) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Customer information</h5></div>
                <div class="info-list">
                    <div><span>Customer</span><strong>{{ $invoice->customer->FullName ?? ($invoice->appointment->customer->FullName ?? 'N/A') }}</strong></div>
                    <div><span>Phone</span><strong>{{ $invoice->customer->Phone ?? ($invoice->appointment->customer->Phone ?? '—') }}</strong></div>
                    <div><span>Appointment</span><strong>#{{ $invoice->AppointmentID }}</strong></div>
                    <div><span>Payment method</span><strong>{{ $invoice->PaymentMethod ?: '—' }}</strong></div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Services used</h5></div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Service</th><th>Qty</th><th>Unit price</th><th>Total</th></tr></thead>
                        <tbody>
                            @forelse ($invoice->appointment->services ?? [] as $line)
                                <tr>
                                    <td>{{ $line->service->ServiceName ?? '—' }}</td>
                                    <td>{{ $line->Quantity }}</td>
                                    <td>{{ number_format($line->UnitPrice, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($line->Quantity * $line->UnitPrice, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">No service data available.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="info-list mt-3">
                    <div><span>Subtotal</span><strong>{{ number_format($invoice->TotalAmount, 0, ',', '.') }}đ</strong></div>
                    <div><span>Discount</span><strong>{{ number_format($invoice->DiscountAmount, 0, ',', '.') }}đ</strong></div>
                    <div><span>Total</span><strong class="text-primary fs-5">{{ number_format($invoice->FinalAmount, 0, ',', '.') }}đ</strong></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
