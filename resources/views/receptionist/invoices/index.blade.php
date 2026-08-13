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

    <div class="panel">
        <div class="panel-header">
            <form class="d-flex" method="GET">
                <input type="search" name="q" value="{{ $keyword }}" class="form-control table-search" placeholder="Search by name or phone...">
                <button class="btn btn-light ms-2" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        @if ($invoices->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                No invoices found.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Khách hàng</th>
                            <th>Lịch hẹn</th>
                            <th>Ngày lập</th>
                            <th>Tổng tiền</th>
                            <th>Giảm giá</th>
                            <th>Thành tiền</th>
                            <th>Thanh toán</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>#{{ $invoice->InvoiceID }}</td>
                                <td>
                                    <a href="{{ route('receptionist.invoices.show', $invoice) }}" class="fw-semibold text-decoration-none">
                                        {{ $invoice->customer->FullName ?? ($invoice->appointment->customer->FullName ?? 'N/A') }}
                                    </a>
                                </td>
                                <td>#{{ $invoice->AppointmentID }}</td>
                                <td>{{ $invoice->InvoiceDate?->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($invoice->TotalAmount, 0, ',', '.') }}đ</td>
                                <td>{{ number_format($invoice->DiscountAmount, 0, ',', '.') }}đ</td>
                                <td class="fw-semibold">{{ number_format($invoice->FinalAmount, 0, ',', '.') }}đ</td>
                                <td>{{ $invoice->PaymentMethod ?: '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('receptionist.invoices.show', $invoice) }}" class="btn btn-sm btn-light" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('receptionist.invoices.edit', $invoice) }}" class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('receptionist.invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this invoice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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
