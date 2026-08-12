@extends('layouts.receptionist.app')

@section('title', 'Customer profile')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>{{ $customer->FullName }}</h1>
            <p class="text-muted mb-0">{{ $customer->Phone }} @if($customer->Email) &middot; {{ $customer->Email }} @endif</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.create', ['customer_id' => $customer->CustomerID]) }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> New appointment
            </a>
            <a href="{{ route('receptionist.customers.edit', $customer) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('receptionist.customers.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Customer information</h5></div>
                <div class="info-list">
                    <div><span>Membership tier</span><strong>{{ $customer->MembershipTier ?: 'Normal' }}</strong></div>
                    <div><span>Loyalty points</span><strong>{{ $customer->LoyaltyPoints ?? 0 }}</strong></div>
                    <div><span>Date of birth</span><strong>{{ $customer->DOB ?: '—' }}</strong></div>
                    <div><span>Allergies</span><strong>{{ $customer->Allergies ?: '—' }}</strong></div>
                </div>
                @if ($customer->Notes)
                    <p class="text-muted mt-3 mb-0">{{ $customer->Notes }}</p>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel mb-3">
                <div class="panel-header"><h5 class="mb-0">Appointment history</h5></div>
                @if ($customer->appointments->isEmpty())
                    <div class="blank-panel blank-state text-center py-4 text-muted">No appointments yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Ngày</th><th>Giờ</th><th>Dịch vụ</th><th>Trạng thái</th></tr></thead>
                            <tbody>
                                @foreach ($customer->appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->AppointmentDate }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i') }}</td>
                                        <td>{{ $appointment->services->pluck('service.ServiceName')->filter()->join(', ') ?: '—' }}</td>
                                        <td><span class="badge text-bg-secondary">{{ $appointment->Status }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Invoices</h5></div>
                @if ($customer->invoices->isEmpty())
                    <div class="blank-panel blank-state text-center py-4 text-muted">No invoices yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Mã HĐ</th><th>Ngày</th><th>Thành tiền</th><th>Thanh toán</th></tr></thead>
                            <tbody>
                                @foreach ($customer->invoices as $invoice)
                                    <tr>
                                        <td><a href="{{ route('receptionist.invoices.show', $invoice) }}">#{{ $invoice->InvoiceID }}</a></td>
                                        <td>{{ $invoice->InvoiceDate?->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($invoice->FinalAmount, 0, ',', '.') }}đ</td>
                                        <td>{{ $invoice->PaymentMethod ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
