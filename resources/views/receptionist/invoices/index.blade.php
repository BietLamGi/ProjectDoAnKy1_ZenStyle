@extends('layouts.receptionist.app')

@section('title', 'Invoices')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Invoices</h1>
            <p class="text-muted mb-0">Create and review service invoices for customers.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create invoice
            </a>
        </div>
    </div>

    @if ($unconfirmedCount > 0)
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-exclamation-triangle"></i>
            {{ $unconfirmedCount }} customer(s)/invoice(s) awaiting payment confirmation.
        </span>
        <a href="{{ route('receptionist.invoices.index', ['unconfirmed' => 1]) }}" class="btn btn-sm btn-outline-dark">
            Show them
        </a>
    </div>
    @endif

    <div class="panel">
        <div class="panel-header">
            <form class="d-flex flex-wrap gap-2 align-items-center" method="GET">
                <input type="search" name="q" value="{{ $keyword }}" class="form-control table-search"
                    placeholder="Search by name or phone...">

                @if ($onlyUnconfirmed)
                <input type="hidden" name="unconfirmed" value="1">
                @endif

                <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>

                @if ($onlyUnconfirmed)
                <a href="{{ route('receptionist.invoices.index', ['q' => $keyword]) }}"
                    class="btn btn-outline-secondary">
                    <i class="bi bi-list-ul"></i> Show all invoices
                </a>
                @else
                <a href="{{ route('receptionist.invoices.index', ['q' => $keyword, 'unconfirmed' => 1]) }}"
                    class="btn btn-outline-warning">
                    <i class="bi bi-funnel"></i> Show unpaid only
                </a>
                @endif
            </form>
        </div>

        @if ($appointmentsAwaitingInvoice->isNotEmpty())
        <div class="blank-panel px-3 pt-3">
            <h6 class="text-muted mb-2">Appointments awaiting invoice ({{ $appointmentsAwaitingInvoice->count() }})</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Appointment</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointmentsAwaitingInvoice as $appointment)
                        <tr>
                            <td>#{{ $appointment->AppointmentID }}</td>
                            <td>{{ $appointment->customer->FullName ?? 'N/A' }}</td>
                            <td>{{ $appointment->AppointmentDate?->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $appointment->Status }}</span>
                            </td>
                            <td class="text-end">
                                @if ($appointment->Status === 'CheckedIn')
                                <a href="{{ route('receptionist.invoices.create', ['appointment_id' => $appointment->AppointmentID]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i> Create invoice
                                </a>
                                @else
                                <span class="text-muted small"
                                    title="Customer must be checked in before this appointment can be invoiced.">
                                    Not checked in yet
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if ($invoices->isEmpty())
        @unless ($appointmentsAwaitingInvoice->isNotEmpty())
        <div class="blank-panel blank-state text-center py-5 text-muted">
            No invoices found.
        </div>
        @endunless
        @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Appointment</th>
                        <th>Date Issued</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                    <tr>
                        <td>#{{ $invoice->InvoiceID }}</td>
                        <td>
                            <a href="{{ route('receptionist.invoices.show', $invoice) }}"
                                class="fw-semibold text-decoration-none">
                                {{ $invoice->customer->FullName ?? ($invoice->appointment->customer->FullName ?? 'N/A') }}
                            </a>
                        </td>
                        <td>#{{ $invoice->AppointmentID }}</td>
                        <td>{{ $invoice->InvoiceDate?->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($invoice->TotalAmount, 0, ',', '.') }}đ</td>
                        <td>{{ number_format($invoice->DiscountAmount, 0, ',', '.') }}đ</td>
                        <td class="fw-semibold">{{ number_format($invoice->FinalAmount, 0, ',', '.') }}đ</td>
                        <td>
                            {{ $invoice->PaymentMethod ?: '' }}
                            @unless ($invoice->PaymentMethod)
                            <span class="badge text-bg-warning" title="No payment method recorded">Unconfirmed</span>
                            @endunless
                        </td>
                        <td class="text-end">
                            <a href="{{ route('receptionist.invoices.show', $invoice) }}" class="btn btn-sm btn-light"
                                title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if ($invoice->PaymentMethod)
                            <span class="badge text-bg-success">Paid</span>
                            @else
                            <a href="{{ route('receptionist.invoices.edit', $invoice) }}" class="btn btn-sm btn-warning"
                                title="Record payment">
                                <i class="bi bi-cash-coin"></i> Pay
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

</div>
@endsection